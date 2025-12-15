<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Booking Routes
Route::get('/booking', [\App\Http\Controllers\BookingController::class, 'index'])->name('booking.index');
Route::get('/booking/availability', [\App\Http\Controllers\BookingController::class, 'getSeatAvailability'])->name('booking.availability');
Route::get('/booking/price', [\App\Http\Controllers\BookingController::class, 'getPrice'])->name('booking.price');
Route::post('/booking/create', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.create');

// Check Booking
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

// Admin Seat Booking Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/seat-booking', [App\Http\Controllers\Admin\SeatBookingController::class, 'index'])->name('admin.seat-booking');
    Route::get('/seat-booking/availability', [App\Http\Controllers\Admin\SeatBookingController::class, 'getSeatAvailability'])->name('admin.seat-booking.availability');
    Route::post('/seat-booking/bookings', [App\Http\Controllers\Admin\SeatBookingController::class, 'store'])->name('admin.seat-booking.store');
    Route::get('/seat-booking/customers/search', [App\Http\Controllers\Admin\SeatBookingController::class, 'searchCustomer'])->name('admin.seat-booking.customers.search');
    Route::post('/seat-booking/customers', [App\Http\Controllers\Admin\SeatBookingController::class, 'createCustomer'])->name('admin.seat-booking.customers.create');
});

require __DIR__ . '/settings.php';
