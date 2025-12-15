<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use App\Models\Route as TravelRoute;
use App\Models\Customer;
use App\Models\Category;
use App\Models\SeatLayout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeatBookingController extends Controller
{
    /**
     * Show the seat booking interface
     */
    public function index()
    {
        return Inertia::render('admin/seat-booking/index', [
            'routes' => TravelRoute::select('id', 'origin', 'destination')->get(),
            'armadas' => Armada::with('seatLayout', 'category')
                ->select('id', 'name', 'capacity', 'seat_layout_id', 'category_id', 'plate_number')
                ->get(),
            'categories' => Category::select('id', 'name')->get(),
            'seatLayouts' => SeatLayout::select('id', 'capacity', 'layout_type', 'seat_map_config')->get(),
        ]);
    }

    /**
     * Get seat availability for a specific armada and date
     */
    public function getSeatAvailability(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'travel_date' => 'required|date',
            'travel_time' => 'nullable|date_format:H:i',
        ]);

        $armada = Armada::with('seatLayout')->findOrFail($request->armada_id);

        if (!$armada->seatLayout) {
            return response()->json([
                'error' => 'Armada tidak memiliki seat layout',
            ], 400);
        }

        $occupiedSeats = \App\Models\SeatAvailabilityCache::getOccupiedSeats(
            $request->armada_id,
            $request->travel_date,
            $request->travel_time
        );

        return response()->json([
            'seatLayout' => $armada->seatLayout->seat_map_config,
            'occupiedSeats' => $occupiedSeats,
            'capacity' => $armada->capacity,
        ]);
    }

    /**
     * Create a new booking with seat assignments
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
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

        // SECURITY: Get price from database, not from user input (even admin)
        $priceRecord = \App\Models\Price::where('route_id', $validated['route_id'])
            ->where('category_id', $validated['category_id'])
            ->where('is_active', true)
            ->validOn($validated['travel_date'])
            ->first();

        if (!$priceRecord) {
            return response()->json([
                'error' => 'Harga untuk rute dan kategori ini belum tersedia. Silakan atur harga di menu Harga terlebih dahulu.',
            ], 422);
        }

        // Use price from database
        $validated['price_per_person'] = $priceRecord->final_price;
        $validated['total_price'] = $priceRecord->final_price * $validated['total_passengers'];

        // Validate seat availability
        try {
            app(\App\Services\SeatAvailabilityService::class)->validateSeatAvailability(
                $validated['armada_id'],
                $validated['travel_date'],
                $validated['travel_time'] ?? null,
                $validated['selected_seats']
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        // Create booking
        // Status langsung 'confirmed' karena dibuat oleh admin
        $booking = \App\Models\Booking::create([
            'booking_code' => 'BK-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'customer_id' => $validated['customer_id'],
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
            'status' => 'confirmed',
            'selected_seats' => json_encode($validated['selected_seats']),
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
            'message' => 'Booking berhasil dibuat dengan kursi: ' . implode(', ', $validated['selected_seats']),
        ]);
    }

    /**
     * Search or get all customers
     */
    public function searchCustomer(Request $request)
    {
        $search = $request->input('search', '');

        $query = Customer::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->orderBy('name')
            ->limit(100)
            ->get(['id', 'name', 'phone', 'email']);

        return response()->json($customers);
    }

    /**
     * Create new customer
     */
    public function createCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'customer' => $customer,
        ]);
    }
}
