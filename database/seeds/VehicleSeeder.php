<?php

use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Vehicle::create([
            'name' => 'Semi-trailer truck',
            'default_price' => 6000.00,
            'maximum_inventory' => 3,
            'currency' => 'USD',
        ]);

        \App\Models\Vehicle::create([
            'name' => '20 foot swap-body truck',
            'default_price' => 2345.00,
            // assuming 4 from "20 foot swap-body truck" + 5 from "28.5 foot pup trailer", but I may got this wrong
            'maximum_inventory' => 9,
            'currency' => 'EUR',
        ]);

    }
}
