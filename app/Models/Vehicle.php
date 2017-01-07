<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = [
        'name',
        'default_price',
        'maximum_inventory',
        'currency',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
