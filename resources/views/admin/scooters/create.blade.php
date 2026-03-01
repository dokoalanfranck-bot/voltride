@extends('layouts.app')

@section('title', 'Nouvelle Trottinette - VoltRide Admin')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px; max-width: 800px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <a href="{{ route('admin.scooters.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 8px;">← Retour à la liste</a>
        <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">
            ➕ Nouvelle <span style="color: var(--primary);">Trottinette</span>
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

    <!-- Form -->
    <form action="{{ route('admin.scooters.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Basic Info -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">📝 Informations de base</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Nom de la trottinette *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Ex: Volt E-Pro 500">
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-input" placeholder="Description détaillée de la trottinette...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Specs -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Caractéristiques</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Vitesse max (km/h) *</label>
                        <input type="number" name="max_speed" value="{{ old('max_speed', 25) }}" required class="form-input" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau de batterie (%)</label>
                        <input type="number" name="battery_level" value="{{ old('battery_level', 100) }}" class="form-input" min="0" max="100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Tarification</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Prix par heure ($) *</label>
                        <input type="number" name="price_hour" value="{{ old('price_hour', 10) }}" required class="form-input" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix par jour ($)</label>
                        <input type="number" name="price_day" value="{{ old('price_day', 50) }}" class="form-input" min="0" step="0.01">
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Status -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">Localisation & Statut</h3>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Localisation</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="form-input" placeholder="Ex: Centre-ville, Station A">
                </div>

                <div class="form-group">
                    <label class="form-label">Statut *</label>
                    <select name="status" required class="form-input">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>En location</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-body">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 24px;">📷 Images</h3>

                <div class="form-group">
                    <label class="form-label">Télécharger des images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-input" style="padding: 12px;">
                    <p style="color: var(--gray); font-size: 0.85rem; margin-top: 8px;">Formats acceptés: JPG, PNG, GIF. Max 5MB par image.</p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; justify-content: center;">
                Créer la trottinette
            </button>
            <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary btn-lg">
                Annuler
            </a>
        </div>
    </form>
</div>

<style>
    @media (max-width: 600px) {
        .card-body > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
