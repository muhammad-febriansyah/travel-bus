<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'route_id' => 'required|exists:routes,id',
            'category' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'travel_date' => 'required|date',
            'total_passengers' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            // Get armada to find category_id
            $armada = \App\Models\Armada::findOrFail($validated['armada_id']);

            // Create or update customer
            $customer = Customer::updateOrCreate(
                ['phone' => $validated['customer_phone']],
                [
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                ]
            );

            // Generate unique booking code
            $bookingCode = 'BK-' . strtoupper(Str::random(8));
            while (Booking::where('booking_code', $bookingCode)->exists()) {
                $bookingCode = 'BK-' . strtoupper(Str::random(8));
            }

            // Calculate price per person
            $pricePerPerson = $validated['total_price'] / $validated['total_passengers'];

            // Create booking
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'customer_id' => $customer->id,
                'route_id' => $validated['route_id'],
                'armada_id' => $validated['armada_id'],
                'category_id' => $armada->category_id,
                'travel_date' => $validated['travel_date'],
                'total_passengers' => $validated['total_passengers'],
                'price_per_person' => $pricePerPerson,
                'total_price' => $validated['total_price'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return back()->with([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'booking_code' => $booking->booking_code,
            ]);

        } catch (\Exception $e) {
            return back()->withErrors([
                'booking' => 'Terjadi kesalahan saat menyimpan booking: ' . $e->getMessage()
            ]);
        }
    }
}
