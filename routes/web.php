<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ShipmentController::class, 'index']);
Route::post('/create-shipment', [ShipmentController::class, 'createShipment'])->name('create.shipment');
Route::get('/packing-slip/{filename}', [ShipmentController::class, 'showPackingSlip'])->name('show.packing-slip');
