@extends('layouts.admin')

@section('title', 'Nouvelle trottinette')
@section('breadcrumb', 'Nouvelle trottinette')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="page-heading">Nouvelle trottinette</div>
        <div class="page-subtitle">Remplissez les informations de la nouvelle trottinette</div>
    </div>
    <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

<form method="POST" action="{{ route('admin.scooters.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

        {{-- Main info --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Informations générales</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex : VoltRide Pro 3000" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Description de la trottinette...">{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Emplacement</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Ex : Zone A — Centre-ville">
                        @error('location')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tarification</div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Prix / heure (€) *</label>
                            <input type="number" name="price_hour" class="form-control" value="{{ old('price_hour') }}" step="0.01" min="0" placeholder="0.00" required>
                            @error('price_hour')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prix / minute (€) *</label>
                            <input type="number" name="price_minute" class="form-control" value="{{ old('price_minute') }}" step="0.001" min="0" placeholder="0.000" required>
                            @error('price_minute')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix journée (€) <span style="color:var(--txt3);font-weight:400;">(optionnel)</span></label>
                        <input type="number" name="price_day" class="form-control" value="{{ old('price_day') }}" step="0.01" min="0" placeholder="0.00">
                        @error('price_day')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Photos</div>
                    <div class="card-subtitle">JPG, PNG — max 5 Mo chacune</div>
                </div>
                <div class="card-body">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label">Images</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*"
                               style="padding:8px;" id="imageInput">
                        <div class="form-hint">Vous pouvez sélectionner plusieurs images à la fois</div>
                        @error('images.*')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div id="imagePreview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;"></div>
                </div>
            </div>
        </div>

        {{-- Side panel --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Caractéristiques</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Vitesse max (km/h)</label>
                        <input type="number" name="max_speed" class="form-control" value="{{ old('max_speed') }}" step="0.1" min="0" placeholder="25">
                        @error('max_speed')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Niveau de batterie (%)</label>
                        <input type="number" name="battery_level" class="form-control" value="{{ old('battery_level', 100) }}" min="0" max="100">
                        @error('battery_level')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code QR</label>
                        <input type="text" name="qr_code" class="form-control" value="{{ old('qr_code') }}" placeholder="Code unique (optionnel)">
                        @error('qr_code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Publication</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" style="margin-bottom:0;font-size:12px;">
                        <i class="fa-solid fa-circle-info"></i>
                        La trottinette sera créée avec le statut <strong>Disponible</strong> et active par défaut.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                <i class="fa-solid fa-plus"></i> Créer la trottinette
            </button>
        </div>

    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    [...this.files].forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid var(--border);';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
