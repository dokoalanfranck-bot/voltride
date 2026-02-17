<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScooterController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API
Route::get('/scooters', [ScooterController::class, 'apiList']);
Route::get('/scooters/{scooter}', [ScooterController::class, 'apiShow']);
Route::post('/reservations/check-availability', [ReservationController::class, 'apiCheckAvailability']);

// Protected API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

