<?php

namespace App\Http\Controllers;

use App\Models\Scooter;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ScooterController extends Controller
{
    public function index(Request $request): View
    {
        $query = Scooter::where('is_active', true)
            ->where('status', 'available')
            ->with('images', 'reviews');

        // Filtre recherche
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre prix par heure
        if ($request->has('price_hour_min') && $request->filled('price_hour_min')) {
            $query->where('price_hour', '>=', $request->input('price_hour_min'));
        }
        if ($request->has('price_hour_max') && $request->filled('price_hour_max')) {
            $query->where('price_hour', '<=', $request->input('price_hour_max'));
        }

        // Filtre vitesse
        if ($request->has('min_speed') && $request->filled('min_speed')) {
            $query->where('max_speed', '>=', $request->input('min_speed'));
        }

        // Filtre batterie
        if ($request->has('min_battery') && $request->filled('min_battery')) {
            $query->where('battery_level', '>=', $request->input('min_battery'));
        }

        // Tri
        $sort = $request->input('sort', 'name');
        if ($sort === 'price_low') {
            $query->orderBy('price_hour', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price_hour', 'desc');
        } elseif ($sort === 'speed') {
            $query->orderBy('max_speed', 'desc');
        } elseif ($sort === 'battery') {
            $query->orderBy('battery_level', 'desc');
        } else {
            $query->orderBy('name', 'asc');
        }

        $scooters = $query->paginate(12)->appends($request->query());

        return view('scooters.index', compact('scooters'));
    }

    public function show(Scooter $scooter): View
    {
        $scooter->load('images', 'reviews', 'reviews.user');
        $averageRating = $scooter->getAverageRating();

        return view('scooters.show', compact('scooter', 'averageRating'));
    }

    public function apiList(): JsonResponse
    {
        $scooters = Scooter::where('is_active', true)
            ->with('images')
            ->get();

        return response()->json($scooters);
    }

    public function apiShow(Scooter $scooter): JsonResponse
    {
        $scooter->load('images', 'reviews');

        return response()->json($scooter);
    }
}
