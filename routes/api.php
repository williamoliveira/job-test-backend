<?php
use Illuminate\Routing\Router;

/** @var Router $router */


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->resource('vehicle', 'VehicleController', ['only' => [
    'index', 'show'
]]);

$router->resource('date', 'DateController', ['only' => [
    'index', 'show', 'store'
]]);

$router->post('date/bulk', 'DateController@bulkStore');