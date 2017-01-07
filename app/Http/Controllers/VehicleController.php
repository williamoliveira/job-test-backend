<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    public function index()
    {
        return Vehicle::all();
    }


    public function show($id)
    {
        return Vehicle::findOrFail($id);
    }

}
