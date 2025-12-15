<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Route as TravelRoute;
use App\Models\Customer;
use App\Models\Category;
use App\Models\SeatLayout;
use App\Models\Booking as BookingModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Show the public booking interface
     */
    public function index()
    {
        $setting = \App\Models\Setting::first();

        if ($setting) {
            $setting->logo = \App\Support\PublicStorageUrl::make($setting->logo);
        }

        return Inertia::render('booking/index', [
            'setting' => $setting,
            'routes' => TravelRoute::select('id', 'origin', 'destination')->get(),
            'armadas' => Armada::with('seatLayout', 'category')
                ->select('id', 'name', 'capacity', 'seat_layout_id', 'category_id', 'plate_number')
                ->where('is_available', true)
                ->get(),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    /**
     * Get seat availability for a specific armada and date
     * Customer booking: checks ALL bookings for the date (ignoring time)
     */
    public function getSeatAvailability(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'travel_date' => 'required|date',
            'travel_time' => 'nullable',
        ]);

        $armada = Armada::with('seatLayout')->findOrFail($request->armada_id);

        if (!$armada->seatLayout) {
            return response()->json([
                'error' => 'Armada tidak memiliki seat layout',
            ], 400);
        }

        // For customer bookings, get ALL occupied seats for the date (ignore time)
        // This prevents double booking on the same date regardless of time
        $occupiedSeats = \App\Models\SeatAvailabilityCache::getOccupiedSeatsForDate(
            $request->armada_id,
            $request->travel_date
        );

        \Log::info('Customer seat availability check', [
            'armada_id' => $request->armada_id,
            'travel_date' => $request->travel_date,
            'travel_time' => $request->travel_time,
            'occupied_seats' => $occupiedSeats,
        ]);

        return response()->json([
            'seatLayout' => $armada->seatLayout->seat_map_config,
            'occupiedSeats' => $occupiedSeats,
            'capacity' => $armada->capacity,
        ]);
    }

    /**
     * Get price for a route and category combination
     */
    public function getPrice(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'category_id' => 'required|exists:categories,id',
            'travel_date' => 'required|date',
        ]);

        $price = \App\Models\Price::where('route_id', $request->route_id)
            ->where('category_id', $request->category_id)
            ->where('is_active', true)
            ->validOn($request->travel_date)
            ->first();

        if (!$price) {
            return response()->json([
                'error' => 'Harga untuk rute dan kategori ini belum tersedia',
            ], 404);
        }

        return response()->json([
            'price' => $price->price,
            'discount' => $price->discount,
            'final_price' => $price->final_price,
        ]);
    }

    /**
     * Create a new booking (customer self-booking)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Customer info (direct input)
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',

            // Booking info
            'route_id' => 'required|exists:routes,id',
            'armada_id' => 'required|exists:armadas,id',
            'category_id' => 'required|exists:categories,id',
            'travel_date' => 'required|date',
            'travel_time' => 'nullable|date_format:H:i',
            'total_passengers' => 'required|integer|min:1',
            'selected_seats' => 'required|array',
            'selected_seats.*' => 'required|string',
            'pickup_location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // SECURITY: Get price from database, not from user input
        $priceRecord = \App\Models\Price::where('route_id', $validated['route_id'])
            ->where('category_id', $validated['category_id'])
            ->where('is_active', true)
            ->validOn($validated['travel_date'])
            ->first();

        if (!$priceRecord) {
            return response()->json([
                'error' => 'Harga untuk rute dan kategori ini belum tersedia. Silakan hubungi admin.',
            ], 422);
        }

        // Use price from database
        $validated['price_per_person'] = $priceRecord->final_price;
        $validated['total_price'] = $priceRecord->final_price * $validated['total_passengers'];

        // Find or create customer
        $customer = Customer::firstOrCreate(
            [
                'phone' => $validated['customer_phone']
            ],
            [
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
            ]
        );

        // Validate seat availability for the DATE (ignore time)
        // Customer bookings block seats for entire day
        try {
            app(\App\Services\SeatAvailabilityService::class)->validateSeatAvailabilityForDate(
                $validated['armada_id'],
                $validated['travel_date'],
                $validated['selected_seats']
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        // Create booking (status: pending for customer bookings)
        $booking = BookingModel::create([
            'booking_code' => 'BK-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'customer_id' => $customer->id,
            'route_id' => $validated['route_id'],
            'armada_id' => $validated['armada_id'],
            'category_id' => $validated['category_id'],
            'travel_date' => $validated['travel_date'],
            'travel_time' => $validated['travel_time'],
            'total_passengers' => $validated['total_passengers'],
            'price_per_person' => $validated['price_per_person'],
            'total_price' => $validated['total_price'],
            'pickup_location' => $validated['pickup_location'],
            'notes' => $validated['notes'],
            'status' => 'pending', // Customer booking starts with pending
        ]);

        // Reserve seats
        try {
            app(\App\Services\SeatAvailabilityService::class)->reserveSeats(
                $booking->id,
                $validated['selected_seats']
            );
        } catch (\Exception $e) {
            // Rollback
            $booking->delete();

            return response()->json([
                'error' => 'Gagal memesan kursi: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking->load('customer', 'route', 'armada', 'seatAssignments'),
            'booking_code' => $booking->booking_code,
            'message' => 'Booking berhasil dibuat! Kode booking: ' . $booking->booking_code,
        ]);
    }
}
