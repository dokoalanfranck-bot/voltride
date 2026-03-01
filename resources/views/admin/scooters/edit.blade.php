@extends('layouts.app')

@section('title', 'Modifier ' . $scooter->name . ' - VoltRide Admin')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px; max-width: 800px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <a href="{{ route('admin.scooters.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 8px;">← Retour à la liste</a>
        <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">
            ✏️ Modifier <span style="color: var(--primary);">{{ $scooter->name }}</span>
        </h1>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 24px;">
            <p style="font-weight: 700; margin-bottom: 8px;">Veuillez corriger les erreurs suivantes:</p>
            @foreach ($errors->all() as $error)
                <p style="margin: 4px 0;">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.scooters.update', $scooter) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Current Images -->
        @if($scooter->images->count() > 0)
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">📷 Images actuelles</h3>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @foreach($scooter->images as $image)
                            <div style="position: relative;">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $scooter->name }}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: contain; background: var(--dark-lighter);">
                                <button type="button" onclick="deleteImage({{ $image->id }})" style="position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; cursor: pointer; font-size: 0.8rem;">×</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Basic Info -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">📝 Informations de base</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Nom de la trottinette *</label>
                    <input type="text" name="name" value="{{ old('name', $scooter->name) }}" required class="form-input" placeholder="Ex: Volt E-Pro 500">
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="Description détaillée de la trottinette...">{{ old('description', $scooter->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Specs -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">⚡ Caractéristiques</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Vitesse max (km/h) *</label>
                        <input type="number" name="max_speed" value="{{ old('max_speed', $scooter->max_speed) }}" required class="form-input" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau de batterie (%)</label>
                        <input type="number" name="battery_level" value="{{ old('battery_level', $scooter->battery_level) }}" class="form-input" min="0" max="100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">💰 Tarification</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Prix par heure ($) *</label>
                        <input type="number" name="price_hour" value="{{ old('price_hour', $scooter->price_hour) }}" required class="form-input" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix par jour ($)</label>
                        <input type="number" name="price_day" value="{{ old('price_day', $scooter->price_day) }}" class="form-input" min="0" step="0.01">
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Status -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">📍 Localisation & Statut</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Localisation</label>
                    <input type="text" name="location" value="{{ old('location', $scooter->location) }}" class="form-input" placeholder="Ex: Centre-ville, Station A">
                </div>

                <div class="form-group">
                    <label class="form-label">Statut *</label>
                    <select name="status" required class="form-input">
                        <option value="available" {{ old('status', $scooter->status) == 'available' ? 'selected' : '' }}>✓ Disponible</option>
                        <option value="rented" {{ old('status', $scooter->status) == 'rented' ? 'selected' : '' }}>📋 En location</option>
                        <option value="maintenance" {{ old('status', $scooter->status) == 'maintenance' ? 'selected' : '' }}>🔧 En maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- New Images -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">📷 Ajouter des images</h3>

                <div class="form-group">
                    <label class="form-label">Télécharger de nouvelles images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-input" style="padding: 12px;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-top: 8px;">Formats acceptés: JPG, PNG, GIF. Max 5MB par image.</p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; justify-content: center;">
                ✓ Sauvegarder les modifications
            </button>
            <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary btn-lg">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
    function deleteImage(imageId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
            fetch('/admin/scooter-images/' + imageId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de la suppression de l\'image.');
                }
            })
            .catch(() => {
                alert('Erreur lors de la suppression de l\'image.');
            });
        }
    }
</script>

<style>
    @media (max-width: 600px) {
        .card-body > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
