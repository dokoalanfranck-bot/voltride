<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScooterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminScooterController;
use App\Http\Controllers\Admin\AdminReservationController;

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::get('/storage/{path}', [StorageController::class, 'getImage'])
    ->where('path', '.*')->name('storage.image');
Route::get('/logo/{filename}', [StorageController::class, 'getLogo'])->name('storage.logo');

// Public scooter routes
Route::get('/scooters', [ScooterController::class, 'index'])->name('scooters.index');
Route::get('/scooters/{scooter}', [ScooterController::class, 'show'])->name('scooters.show');

// Reservation: create + store are public (guest reservations)
Route::get('/scooters/{scooter}/reserve', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware('throttle:reservations')
    ->name('reservations.store');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

// API
Route::post('/api/reservations/check-availability', [ReservationController::class, 'apiCheckAvailability'])
    ->middleware('throttle:60,1')
    ->name('api.reservations.check');

// Authenticated client routes
Route::middleware('auth')->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('scooters', AdminScooterController::class);

    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
    Route::put('/reservations/{reservation}', [AdminReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reservations/{reservation}/complete', [AdminReservationController::class, 'markCompleted'])->name('reservations.complete');
    Route::post('/reservations/{reservation}/validate-payment', [AdminReservationController::class, 'validatePayment'])->name('reservations.validatePayment');
});

require __DIR__ . '/auth.php';
