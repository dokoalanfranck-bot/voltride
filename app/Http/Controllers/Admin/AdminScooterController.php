<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scooter;
use App\Models\ScooterImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScooterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = Scooter::query();

        // Search by name or brand
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->input('availability') === 'available') {
                $query->where('is_available', true);
            } elseif ($request->input('availability') === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price_hour', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price_hour', '<=', $request->input('price_max'));
        }

        $scooters = $query->latest()->paginate(15);

        return view('admin.scooters.index', compact('scooters'));
    }

    public function create(): View
    {
        return view('admin.scooters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_hour' => 'required|numeric|min:0',
            'price_day' => 'required|numeric|min:0',
            'max_speed' => 'nullable|numeric|min:0',
            'qr_code' => 'nullable|unique:scooters,qr_code',
            'location' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $scooter = Scooter::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('scooters', 'public');
                ScooterImage::create([
                    'scooter_id' => $scooter->id,
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Scooter created successfully');
    }

    public function edit(Scooter $scooter): View
    {
        return view('admin.scooters.edit', compact('scooter'));
    }

    public function update(Request $request, Scooter $scooter): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_hour' => 'required|numeric|min:0',
            'price_day' => 'required|numeric|min:0',
            'battery_level' => 'nullable|integer|min:0|max:100',
            'status' => 'required|in:available,rented,maintenance',
            'max_speed' => 'nullable|numeric|min:0',
            'location' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $scooter->update($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('scooters', 'public');
                ScooterImage::create([
                    'scooter_id' => $scooter->id,
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Scooter updated successfully');
    }

    public function destroy(Scooter $scooter): RedirectResponse
    {
        $scooter->delete();

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Scooter deleted successfully');
    }
}
