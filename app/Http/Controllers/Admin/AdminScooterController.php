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
        $query = Scooter::withCount('reservations');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('price_min')) {
            $query->where('price_hour', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price_hour', '<=', $request->input('price_max'));
        }

        $scooters = $query->with('images')->latest()->paginate(15);

        return view('admin.scooters.index', compact('scooters'));
    }

    public function create(): View
    {
        return view('admin.scooters.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_hour'  => 'required|numeric|min:0',
            'price_minute'=> 'required|numeric|min:0',
            'price_day'   => 'nullable|numeric|min:0',
            'battery_level' => 'nullable|integer|min:0|max:100',
            'max_speed'   => 'nullable|numeric|min:0',
            'qr_code'     => 'nullable|string|unique:scooters,qr_code',
            'location'    => 'nullable|string|max:255',
            'images.*'    => 'nullable|image|max:5120',
        ]);

        $validated['status']    = 'available';
        $validated['is_active'] = true;

        $scooter = Scooter::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('scooters', 'public');
                ScooterImage::create([
                    'scooter_id' => $scooter->id,
                    'image_path' => $path,
                    'order'      => $index,
                ]);
            }
        }

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Trottinette créée avec succès.');
    }

    public function edit(Scooter $scooter): View
    {
        return view('admin.scooters.edit', compact('scooter'));
    }

    public function update(Request $request, Scooter $scooter): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price_hour'   => 'required|numeric|min:0',
            'price_minute' => 'required|numeric|min:0',
            'price_day'    => 'nullable|numeric|min:0',
            'battery_level'=> 'nullable|integer|min:0|max:100',
            'status'       => 'required|in:available,rented,maintenance',
            'max_speed'    => 'nullable|numeric|min:0',
            'location'     => 'nullable|string|max:255',
            'is_active'    => 'nullable|boolean',
            'images.*'     => 'nullable|image|max:5120',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $scooter->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('scooters', 'public');
                ScooterImage::create([
                    'scooter_id' => $scooter->id,
                    'image_path' => $path,
                    'order'      => $scooter->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Trottinette mise à jour avec succès.');
    }

    public function destroy(Scooter $scooter): RedirectResponse
    {
        $scooter->images()->each(fn ($img) => \Storage::disk('public')->delete($img->image_path));
        $scooter->images()->delete();
        $scooter->delete();

        return redirect()->route('admin.scooters.index')
            ->with('success', 'Trottinette supprimée.');
    }
}
