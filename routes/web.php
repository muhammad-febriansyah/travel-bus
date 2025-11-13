<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cek-booking', [HomeController::class, 'cekBooking'])->name('cek-booking');
Route::post('/cek-booking/search', [HomeController::class, 'searchBooking'])->name('cek-booking.search');
Route::get('/booking/{bookingCode}', [HomeController::class, 'showBooking'])->name('booking.show');
Route::get('/booking/{bookingCode}/invoice', [HomeController::class, 'downloadInvoice'])->name('booking.invoice');

// API Routes
Route::post('/api/bookings', [BookingController::class, 'store'])->name('api.bookings.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
