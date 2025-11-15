<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Route;
use App\Models\Setting;
use App\Support\PublicStorageUrl;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        // Add Storage URL to logo and hero image
        if ($setting) {
            $setting->logo = PublicStorageUrl::make($setting->logo);
            $setting->hero_image = PublicStorageUrl::make($setting->hero_image);
        }

        // Ambil data untuk landing page
        $routes = Route::where('is_active', true)
            ->with(['prices.category'])
            ->limit(6)
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'origin' => $route->origin,
                    'destination' => $route->destination,
                    'route_code' => $route->route_code,
                    'distance' => $route->distance,
                    'estimated_duration' => $route->estimated_duration,
                    'prices' => $route->prices->map(function ($price) {
                        return [
                            'category' => $price->category->name,
                            'price' => $price->final_price,
                        ];
                    }),
                ];
            });

        $armadas = Armada::where('is_available', true)
            ->with('category')
            ->get()
            ->map(function ($armada) {
                return [
                    'id' => $armada->id,
                    'name' => $armada->name,
                    'vehicle_type' => $armada->vehicle_type,
                    'capacity' => $armada->capacity,
                    'category' => $armada->category->name,
                    'description' => strip_tags($armada->description),
                    'image' => PublicStorageUrl::make($armada->image),
                ];
            });

        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ];
        });

        $heroSlides = HeroSlide::active()
            ->ordered()
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'description' => $slide->description,
                    'image' => PublicStorageUrl::make($slide->image),
                    'badge_text' => $slide->badge_text,
                    'primary_button_text' => $slide->primary_button_text,
                    'primary_button_url' => $slide->primary_button_url,
                    'secondary_button_text' => $slide->secondary_button_text,
                    'secondary_button_url' => $slide->secondary_button_url,
                    'rating_text' => $slide->rating_text,
                    'rating_value' => $slide->rating_value,
                ];
            });

        return Inertia::render('Home/Index', [
            'setting' => $setting,
            'heroSlides' => $heroSlides,
            'routes' => $routes,
            'armadas' => $armadas,
            'categories' => $categories,
        ]);
    }

    public function cekBooking()
    {
        $setting = Setting::first();

        if ($setting) {
            $setting->logo = PublicStorageUrl::make($setting->logo);
        }

        return Inertia::render('Home/CekBooking', [
            'setting' => $setting,
        ]);
    }

    public function searchBooking(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string',
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)
            ->with(['customer', 'route', 'armada', 'category'])
            ->first();

        if (!$booking) {
            return back()->withErrors([
                'booking_code' => 'Kode booking tidak ditemukan.',
            ]);
        }

        return redirect()->route('booking.show', $booking->booking_code);
    }

    public function showBooking($bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->with(['customer', 'route', 'armada', 'category'])
            ->firstOrFail();

        $setting = Setting::first();

        if ($setting) {
            $setting->logo = PublicStorageUrl::make($setting->logo);
        }

        return Inertia::render('Home/BookingDetail', [
            'setting' => $setting,
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'status' => $booking->status,
                'customer' => [
                    'name' => $booking->customer->name,
                    'phone' => $booking->customer->phone,
                    'email' => $booking->customer->email,
                ],
                'route' => [
                    'origin' => $booking->route->origin,
                    'destination' => $booking->route->destination,
                    'route_code' => $booking->route->route_code,
                ],
                'armada' => [
                    'name' => $booking->armada->name,
                    'plate_number' => $booking->armada->plate_number,
                    'vehicle_type' => $booking->armada->vehicle_type,
                ],
                'category' => $booking->category->name,
                'travel_date' => $booking->travel_date->format('d M Y'),
                'travel_time' => $booking->travel_time?->format('H:i'),
                'pickup_location' => $booking->pickup_location,
                'total_passengers' => $booking->total_passengers,
                'price_per_person' => $booking->price_per_person,
                'total_price' => $booking->total_price,
                'notes' => $booking->notes,
                'created_at' => $booking->created_at->format('d M Y H:i'),
            ],
        ]);
    }

    public function downloadInvoice($bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->with(['customer', 'route', 'armada', 'category'])
            ->firstOrFail();

        $setting = Setting::first();

        $pdf = Pdf::loadView('invoices.booking', [
            'booking' => $booking,
            'setting' => $setting,
        ]);

        return $pdf->download('invoice-' . $booking->booking_code . '.pdf');
    }
}
