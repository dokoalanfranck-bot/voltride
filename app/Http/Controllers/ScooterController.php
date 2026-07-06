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

        if ($request->filled('price_min')) {
            $query->where('price_hour', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price_hour', '<=', $request->input('price_max'));
        }
        if ($request->filled('speed_min')) {
            $query->where('max_speed', '>=', $request->input('speed_min'));
        }
        if ($request->filled('battery_min')) {
            $query->where('battery_level', '>=', $request->input('battery_min'));
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
