@extends('layouts.app')

@section('content')
<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .bg-white {
        animation: slideIn 0.6s ease-out;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #07d65d !important;
        box-shadow: 0 0 0 3px rgba(7, 214, 93, 0.2) !important;
        outline: none;
    }
</style>
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Scooter</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.scooters.update', $scooter) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Name *</label>
                <input type="text" name="name" required value="{{ $scooter->name }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ $scooter->description }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Price/Hour ($) *</label>
                    <input type="number" name="price_hour" required step="0.01" value="{{ $scooter->price_hour }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Price/Day ($) *</label>
                    <input type="number" name="price_day" required step="0.01" value="{{ $scooter->price_day }}">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Battery Level (%)</label>
                    <input type="number" name="battery_level" min="0" max="100" value="{{ $scooter->battery_level }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="available" @selected($scooter->status === 'available')>Available</option>
                        <option value="rented" @selected($scooter->status === 'rented')>Rented</option>
                        <option value="maintenance" @selected($scooter->status === 'maintenance')>Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Max Speed (km/h)</label>
                    <input type="number" name="max_speed" step="0.1" value="{{ $scooter->max_speed }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Location</label>
                    <input type="text" name="location" value="{{ $scooter->location }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" @checked($scooter->is_active) class="rounded">
                    <span class="ml-2">Active</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Images</label>
                <div class="mb-2">
                    @forelse($scooter->images as $image)
                        <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}" style="width: 100px; height: 100px; object-fit: contain; border-radius: 4px; margin-right: 8px; margin-bottom: 8px; display: inline-block; background: #f9f9f9; padding: 5px;">
                    @empty
                        <p class="text-gray-500">No images yet</p>
                    @endforelse
                </div>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-600 mt-1">You can upload additional images</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Scooter
                </button>
                <a href="{{ route('admin.scooters.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
