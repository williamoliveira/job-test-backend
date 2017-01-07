<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DateTest extends TestCase
{
    use DatabaseTransactions;

    public function testApiIndex()
    {
        $from = Carbon::now()->toW3cString();
        $to = Carbon::now()->addDays(11)->toW3cString();

        $this->makeRequest('GET', '/api/date', compact('from', 'to'))
            ->seeStatusCode(200)
            ->seeJsonStructure([
                [
                    'date',
                    'schedules' => [
                        '*' => [
                            'price',
                            'inventory'
                        ]
                    ]
                ]
            ]);

        $this->assertEquals($this->response->getOriginalContent()->count(), 12);
    }

    public function testApiShow()
    {
        $date = Carbon::now()->toW3cString();

        $this->visit("/api/date/{$date}")
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'date',
                'schedules' => [
                    '*' => [
                        'price',
                        'inventory'
                    ]
                ]
            ]);
    }

    public function testApiStore()
    {
        $vehicle = \App\Models\Vehicle::first();

        // First time on that date
        $data = [
            'vehicle_id' => $vehicle->id,
            'date' => Carbon::tomorrow()->toW3cString(),
            'price' => 1337
        ];

        $this->makeRequest('POST', '/api/date', $data)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'date',
                'schedules' => [
                    '*' => [
                        'price',
                        'inventory'
                    ]
                ]
            ])
            ->seeInDatabase('schedules', array_merge($data, [
                'inventory' => $vehicle->maximum_inventory
            ]));


        // Already exists in that date (from previous assertion)
        $newData = array_merge($data, ['price' => 2337]);

        $this->makeRequest('POST', '/api/date', $newData)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'date',
                'schedules' => [
                    '*' => [
                        'price',
                        'inventory'
                    ]
                ]
            ])
            ->seeInDatabase('schedules', array_merge($newData, [
                'inventory' => $vehicle->maximum_inventory
            ]));
    }

    public function testApiBulkStoreMondaysAndFridays()
    {
        $from = Carbon::now()->next(Carbon::MONDAY);
        $to = (clone $from)->next(Carbon::SATURDAY);

        $data = [
            'vehicle_id' => 1,
            'from' => $from->toW3cString(),
            'to' => $to->toW3cString(),
            'price' => 1337,
            'inventory' => 3,
            'refine' => ['mondays', 'fridays']
        ];

        $this->makeRequest('POST', '/api/date/bulk', $data)
            ->seeStatusCode(200)
            ->see(2);
    }

    public function testApiBulkStoreAll()
    {
        $from = Carbon::now()->next(Carbon::MONDAY);
        $to = (clone $from)->next(Carbon::SATURDAY);

        $data = [
            'vehicle_id' => 1,
            'from' => $from->toW3cString(),
            'to' => $to->toW3cString(),
            'price' => 1337,
            'inventory' => 3,
            'refine' => ['all']
        ];

        $this->makeRequest('POST', '/api/date/bulk', $data)
            ->seeStatusCode(200)
            ->see(6);
    }

    public function testApiBulkStoreNoRefine()
    {
        $from = Carbon::now()->next(Carbon::MONDAY);
        $to = (clone $from)->next(Carbon::SATURDAY);

        $data = [
            'vehicle_id' => 1,
            'from' => $from->toW3cString(),
            'to' => $to->toW3cString(),
            'price' => 1337,
            'inventory' => 3
        ];

        $this->makeRequest('POST', '/api/date/bulk', $data)
            ->seeStatusCode(200)
            ->see(6);
    }
}
