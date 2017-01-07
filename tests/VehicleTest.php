<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VehicleTest extends TestCase
{
    use DatabaseTransactions;

    public function testDatabase()
    {
        $this->seeInDatabase('vehicles', [
            'name' => 'Semi-trailer truck',
            'default_price' => 6000.00,
            'maximum_inventory' => 3,
            'currency' => 'USD',
        ]);

        $this->seeInDatabase('vehicles', [
            'name' => '20 foot swap-body truck',
            'default_price' => 2345.00,
            'maximum_inventory' => 9,
            'currency' => 'EUR',
        ]);
    }

    public function testApiIndex()
    {
        $this->visit('/api/vehicle')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                [
                    'name',
                    'default_price',
                    'maximum_inventory',
                    'currency',
                ]
            ])
            ->seeJsonSubset([
                [
                    'name' => 'Semi-trailer truck',
                    'default_price' => 6000.00,
                    'maximum_inventory' => 3,
                    'currency' => 'USD',
                ],
                [
                    'name' => '20 foot swap-body truck',
                    'default_price' => 2345.00,
                    'maximum_inventory' => 9,
                    'currency' => 'EUR',
                ]
            ]);
    }

    public function testApiShow()
    {
        $this->visit('/api/vehicle/1')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'name',
                'default_price',
                'maximum_inventory',
                'currency'
            ])
            ->seeJsonSubset([
                'name' => 'Semi-trailer truck',
                'default_price' => 6000.00,
                'maximum_inventory' => 3,
                'currency' => 'USD',
            ]);
    }
}
