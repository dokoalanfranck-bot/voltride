@extends('layouts.admin')

@section('title', 'Modifier — '.$scooter->name)
@section('breadcrumb', 'Modifier '.$scooter->name)

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <div class="page-heading">{{ $scooter->name }}</div>
        <div class="page-subtitle">Modifiez les informations de cette trottinette</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.scooters.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
        <form method="POST" action="{{ route('admin.scooters.destroy', $scooter) }}"
              onsubmit="return confirm('Supprimer définitivement cette trottinette ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fa-solid fa-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<form method="POST" action="{{ route('admin.scooters.update', $scooter) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><div class="card-title">Informations générales</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $scooter->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $scooter->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Emplacement</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $scooter->location) }}">
                        @error('location')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title">Tarification</div></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Prix / heure (€) *</label>
                            <input type="number" name="price_hour" class="form-control" value="{{ old('price_hour', $scooter->price_hour) }}" step="0.01" min="0" required>
                            @error('price_hour')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prix / minute (€) *</label>
                            <input type="number" name="price_minute" class="form-control" value="{{ old('price_minute', $scooter->price_minute) }}" step="0.001" min="0" required>
                            @error('price_minute')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix journée (€)</label>
                        <input type="number" name="price_day" class="form-control" value="{{ old('price_day', $scooter->price_day) }}" step="0.01" min="0">
                        @error('price_day')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Existing images --}}
            @if($scooter->images->count())
            <div class="card">
                <div class="card-header"><div class="card-title">Photos actuelles</div></div>
                <div class="card-body">
                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        @foreach($scooter->images as $img)
                        <div style="position:relative;">
                            <img src="{{ route('storage.image', $img->image_path) }}"
                                 style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header"><div class="card-title">Ajouter des photos</div></div>
                <div class="card-body">
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="imageInput" style="padding:8px;">
                    <div class="form-hint">Les nouvelles images seront ajoutées aux existantes</div>
                    <div id="imagePreview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;"></div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><div class="card-title">Statut & Disponibilité</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Statut *</label>
                        <select name="status" class="form-control">
                            <option value="available"   {{ old('status',$scooter->status)==='available'   ? 'selected':'' }}>Disponible</option>
                            <option value="rented"      {{ old('status',$scooter->status)==='rented'      ? 'selected':'' }}>En location</option>
                            <option value="maintenance" {{ old('status',$scooter->status)==='maintenance' ? 'selected':'' }}>Maintenance</option>
                        </select>
                        @error('status')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $scooter->is_active) ? 'checked' : '' }}
                                   style="width:16px;height:16px;accent-color:var(--primary);cursor:pointer;">
                            <span style="font-size:13px;font-weight:500;color:var(--txt);">Trottinette active</span>
                        </label>
                        <div class="form-hint">Une trottinette inactive n'apparaît pas sur le site</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title">Caractéristiques</div></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Vitesse max (km/h)</label>
                        <input type="number" name="max_speed" class="form-control" value="{{ old('max_speed', $scooter->max_speed) }}" step="0.1" min="0">
                        @error('max_speed')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Batterie :
                            <span id="battLabel" style="color:#00FF6A;font-weight:600;">{{ old('battery_level', $scooter->battery_level ?? 100) }}%</span>
                        </label>
                        <input type="range" name="battery_level" id="battRange" class="form-control"
                               value="{{ old('battery_level', $scooter->battery_level ?? 100) }}"
                               min="0" max="100" step="1"
                               style="padding:4px 0;cursor:pointer;accent-color:#00FF6A;">
                        @error('battery_level')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Code QR</label>
                        <input type="text" name="qr_code" class="form-control" value="{{ old('qr_code', $scooter->qr_code) }}">
                        @error('qr_code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer les modifications
            </button>
        </div>

    </div>
</form>
@endsection

@section('scripts')
<script>
const battRange = document.getElementById('battRange');
const battLabel = document.getElementById('battLabel');
battRange.addEventListener('input', () => {
    battLabel.textContent = battRange.value + '%';
    const v = parseInt(battRange.value);
    battLabel.style.color = v > 50 ? '#00FF6A' : v > 20 ? '#ffaa00' : '#ff4d4d';
});

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
