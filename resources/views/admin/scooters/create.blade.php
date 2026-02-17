@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Create New Scooter</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.scooters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Name *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Price/Hour ($) *</label>
                    <input type="number" name="price_hour" required step="0.01" value="{{ old('price_hour') }}" class="w-full border rounded px-3 py-2 @error('price_hour') border-red-500 @enderror">
                    @error('price_hour')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Price/Day ($) *</label>
                    <input type="number" name="price_day" required step="0.01" value="{{ old('price_day') }}" class="w-full border rounded px-3 py-2 @error('price_day') border-red-500 @enderror">
                    @error('price_day')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Max Speed (km/h)</label>
                    <input type="number" name="max_speed" step="0.1" value="{{ old('max_speed') }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">QR Code</label>
                    <input type="text" name="qr_code" value="{{ old('qr_code') }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
                <p class="text-sm text-gray-600 mt-1">You can upload multiple images</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Create Scooter
                </button>
                <a href="{{ route('admin.scooters.index') }}" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
