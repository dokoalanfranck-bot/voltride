<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScooterController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminScooterController;
use App\Http\Controllers\Admin\AdminReservationController;

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
})->name('welcome');

// Public Scooter Routes
Route::get('/scooters', [ScooterController::class, 'index'])->name('scooters.index');
Route::get('/scooters/{scooter}', [ScooterController::class, 'show'])->name('scooters.show');

// Client Routes
Route::middleware('auth')->group(function () {
    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/scooters/{scooter}/reserve', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Payments
    Route::get('/reservations/{reservation}/payment', [PaymentController::class, 'show'])->name('reservations.payment');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Scooter Management
    Route::resource('scooters', AdminScooterController::class, [
        'as' => 'admin'
    ]);

    // Reservation Management
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('admin.reservations.index');
    Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations/{reservation}/complete', [AdminReservationController::class, 'markCompleted'])->name('admin.reservations.complete');
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('admin.payments.refund');
});

// Authentication Routes
require __DIR__.'/auth.php';
