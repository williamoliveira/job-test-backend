<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'vehicle_id',
        'date',
        'price',
        'inventory',
    ];

    protected $dates = [
        'date'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
