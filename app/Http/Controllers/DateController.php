<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Vehicle;
use App\Services\DateRefiner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DateController extends Controller
{

    public function index(Request $request)
    {
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'));

        $vehicles = Vehicle::all();

        $schedules = Schedule::query()
            ->whereBetween('date', [$from, $to])
            ->get();

        $date = collect();

        for($current = $from; $current->lte($to); $current = $current->addDay()){
            $date[] = $this->makeForDate($current, $vehicles, $schedules);
        }

        return $date;
    }

    public function show($id)
    {
        $date = Carbon::parse($id);
        $vehicles = Vehicle::all();

        $schedules = Schedule::query()
            ->where('date', $date)
            ->get();

        return $this->makeForDate($date, $vehicles, $schedules);
    }

    public function store(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->get('vehicle_id'));
        $date = Carbon::parse($request->get('date'));

        $this->saveSchedule([
            'vehicle' => $vehicle,
            'date' => $date,
            'price' => $request->get('price'),
            'inventory' => $request->get('inventory'),
        ]);

        $vehicles = Vehicle::all();

        $schedules = Schedule::query()
            ->where('date', $date)
            ->get();

        return $this->makeForDate($date, $vehicles, $schedules);
    }

    public function bulkStore(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->get('vehicle_id'));
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'));
        $refine = $request->get('refine');

        $count = 0;

        for($current = $from; $current->lte($to); $current = $current->addDay()){
            if(!DateRefiner::datePassRefine($current, $refine)) continue;

            $this->saveSchedule([
                'vehicle' => $vehicle,
                'date' => $current,
                'price' => $request->get('price'),
                'inventory' => $request->get('inventory'),
            ]);

            $count++;
        }

        return $count;
    }

    protected function saveSchedule($data)
    {
        $vehicle = $data['vehicle'];
        $date = $data['date'];
        $price = isset($data['price']) ? $data['price'] : null;
        $inventory = isset($data['inventory']) ? $data['inventory'] : null;

        $schedule = Schedule::query()
            ->where('vehicle_id', $vehicle->id)
            ->where('date', $date)
            ->first();

        if (!empty($schedule)) {
            return $schedule->update([
                'price' => $price ?: $schedule->price,
                'inventory' => $inventory ?: $schedule->inventory,
            ]);
        }

        return Schedule::create([
            'vehicle_id' => $vehicle->id,
            'date' => $date,
            'price' => $price ?: $vehicle->default_price,
            'inventory' => $inventory ?: $vehicle->maximum_inventory,
        ]);
    }

    protected function makeForDate(Carbon $date, Collection $vehicles, Collection $schedules)
    {
        $currentDaySchedules = $schedules->filter(function($schedule) use ($date){
            return $date->isSameDay($schedule->date);
        });

        $dateSchedules = [];

        foreach ($vehicles as $vehicle){
            /** @var Schedule $schedule */
            $schedule = $currentDaySchedules->where('vehicle_id', $vehicle->id)->first();

            $dateSchedules[$vehicle->id] = [
                'price' => !empty($schedule) ? $schedule->price : $vehicle->default_price,
                'inventory' => !empty($schedule) ? $schedule->inventory : $vehicle->maximum_inventory,
            ];
        }

        return [
            'date' => $date->toW3cString(),
            'schedules' => $dateSchedules
        ];
    }
}
