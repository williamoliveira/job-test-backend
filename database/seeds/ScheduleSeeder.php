<?php

use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicle = \App\Models\Vehicle::first();

        $schelule = new \App\Models\Schedule([
            'date' => \Carbon\Carbon::now()->addDays(2),
            'price' => 5000,
            'inventory' => 2
        ]);

        $schelule->vehicle()->associate($vehicle);

        $schelule->save();
    }
}
