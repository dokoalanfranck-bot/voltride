@extends('layouts.app')

@section('title', 'Réserver ' . $scooter->name . ' - VoltRide')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <!-- Header -->
    <div style="margin-bottom: 40px; text-align: center;">
        <span class="badge badge-success" style="margin-bottom: 16px;">⚡ Réservation rapide</span>
        <h1 style="font-size: clamp(1.8rem, 5vw, 2.5rem); font-weight: 800; margin-bottom: 12px; letter-spacing: -1px;">
            Réserver <span style="color: var(--primary);">{{ $scooter->name }}</span>
        </h1>
        <p style="color: var(--gray); font-size: 1.1rem; max-width: 500px; margin: 0 auto;">
            Complétez le formulaire ci-dessous pour réserver votre trottinette
        </p>
    </div>

    <!-- Main Grid -->
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 40px;">
        
        <!-- Form Section -->
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="scooter_id" value="{{ $scooter->id }}">

            <!-- Scooter Card -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body" style="display: flex; gap: 20px; align-items: center;">
                    @if($scooter->images->count() > 0)
                        <img src="{{ asset('storage/' . $scooter->images->first()->image_path) }}" alt="{{ $scooter->name }}" style="width: 100px; height: 100px; border-radius: 12px; object-fit: contain; background: var(--dark-lighter);">
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 12px; background: var(--dark-lighter); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; opacity: 0.5;">🛴</div>
                    @endif
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 4px; color: var(--primary);">{{ $scooter->name }}</h3>
                        <p style="color: var(--gray); margin-bottom: 8px; line-height: 1.5;">{{ Str::limit($scooter->description, 100) }}</p>
                        <div style="display: flex; gap: 16px; font-size: 0.9rem;">
                            <span>🔋 {{ $scooter->battery_level ?? 100 }}%</span>
                            <span>⚡ {{ $scooter->max_speed }} km/h</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date & Time Section -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 1.3rem;">📅</span> Quand?
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Date</label>
                            <input type="date" id="start_date" name="start_date" required class="form-input" min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}">
                            @error('start_date') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="form-label">Heure</label>
                            <input type="time" id="start_time" name="start_time" required class="form-input" value="{{ old('start_time', '09:00') }}">
                            @error('start_time') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration Section -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 1.3rem;">⏱️</span> Durée
                    </h3>
                    
                    <div style="margin-bottom: 20px;">
                        <input type="range" id="duration_minutes" name="duration_minutes" min="30" max="120" step="15" value="{{ old('duration_minutes', 30) }}" 
                            style="width: 100%; height: 8px; border-radius: 5px; background: var(--dark-lighter); outline: none; -webkit-appearance: none; cursor: pointer;">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px;">
                        <button type="button" class="duration-btn btn btn-secondary" data-minutes="30" style="padding: 12px;">30 min</button>
                        <button type="button" class="duration-btn btn btn-secondary" data-minutes="45" style="padding: 12px;">45 min</button>
                        <button type="button" class="duration-btn btn btn-secondary" data-minutes="60" style="padding: 12px;">1h</button>
                        <button type="button" class="duration-btn btn btn-secondary" data-minutes="120" style="padding: 12px;">2h</button>
                    </div>

                    <div id="duration-display" style="font-size: 2rem; font-weight: 800; color: var(--primary); text-align: center; margin-bottom: 16px;">30 min</div>

                    <div class="alert alert-warning" style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 1.2rem;">ℹ️</span>
                        <span>Seuls les touristes peuvent réserver pour 2 heures maximum.</span>
                    </div>

                    @error('duration_minutes') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 12px;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tourist Selection -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 1.3rem;">🌍</span> Vous êtes?
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <label class="card" style="padding: 20px; cursor: pointer; text-align: center; transition: all 0.3s; border: 2px solid transparent;" id="local-option">
                            <input type="radio" name="is_tourist" value="0" {{ old('is_tourist', 0) == 0 ? 'checked' : '' }} style="display: none;">
                            <div style="font-size: 2rem; margin-bottom: 8px;">👤</div>
                            <div style="font-weight: 700; color: var(--primary);">Local</div>
                            <p style="color: var(--gray); font-size: 0.85rem; margin-top: 4px;">Max 1h</p>
                        </label>
                        <label class="card" style="padding: 20px; cursor: pointer; text-align: center; transition: all 0.3s; border: 2px solid transparent;" id="tourist-option">
                            <input type="radio" name="is_tourist" value="1" {{ old('is_tourist') == 1 ? 'checked' : '' }} style="display: none;">
                            <div style="font-size: 2rem; margin-bottom: 8px;">✈️</div>
                            <div style="font-weight: 700; color: var(--primary);">Touriste</div>
                            <p style="color: var(--gray); font-size: 0.85rem; margin-top: 4px;">Jusqu'à 2h</p>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Guest Info Section -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 1.3rem;">👤</span> Vos informations
                    </h3>
                    
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="guest_name" class="form-label">Nom complet *</label>
                        <input type="text" id="guest_name" name="guest_name" required class="form-input" value="{{ old('guest_name', auth()->user()?->name) }}" placeholder="Jean Dupont">
                        @error('guest_name') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="guest_phone" class="form-label">Téléphone *</label>
                        <input type="tel" id="guest_phone" name="guest_phone" required class="form-input" value="{{ old('guest_phone') }}" placeholder="+33 6 12 34 56 78">
                        @error('guest_phone') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="guest_email" class="form-label">Email *</label>
                        <input type="email" id="guest_email" name="guest_email" required class="form-input" value="{{ old('guest_email', auth()->user()?->email) }}" placeholder="jean@example.com">
                        @error('guest_email') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Terms Acceptance -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-body">
                    <label style="display: flex; gap: 12px; cursor: pointer; align-items: flex-start;">
                        <input type="checkbox" name="accept_terms" value="1" required style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer; flex-shrink: 0; accent-color: var(--primary);">
                        <span style="color: var(--gray); line-height: 1.6;">
                            J'accepte les <span style="color: var(--white); font-weight: 600;">conditions générales</span>: paiement sur place, responsabilité de la trottinette, caution 50€, assurance incluse
                        </span>
                    </label>
                    @error('accept_terms') <p style="color: #ef4444; font-size: 0.85rem; margin-top: 12px;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                ✓ Finaliser la réservation
            </button>
        </form>

        <!-- Pricing Sidebar -->
        <div style="position: sticky; top: 100px; height: fit-content;">
            <div class="card">
                <div class="card-body">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 1.3rem;">💰</span> Tarifs
                    </h3>
                    
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: var(--gray);">À l'heure</span>
                            <span style="font-weight: 700; color: var(--primary);">{{ number_format($scooter->price_hour, 2) }} $</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: var(--gray);">À la minute</span>
                            <span style="font-weight: 700; color: var(--primary);">{{ number_format($scooter->price_hour / 60, 2) }} $</span>
                        </div>
                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 16px; margin-top: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--gray); font-weight: 600;">Estimation</span>
                                <span id="duration-price" class="price price-lg">{{ number_format($scooter->price_hour / 2, 2) }} $</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: rgba(0, 255, 106, 0.1); border-left: 3px solid var(--primary); padding: 16px; border-radius: 0 8px 8px 0;">
                        <p style="color: var(--primary); font-weight: 600; margin-bottom: 8px;">💡 Info</p>
                        <p style="color: var(--gray); font-size: 0.9rem; margin: 0;">Paiement sur place en espèces ou par carte</p>
                    </div>
                </div>
            </div>

            <!-- Promo Code -->
            <div class="card" style="margin-top: 16px;">
                <div class="card-body">
                    <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 12px;">🎟️ Code promo</h3>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" name="promo_code" class="form-input" placeholder="VOLTRIDE10" style="flex: 1;">
                        <button type="button" class="btn btn-secondary">Appliquer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 900px) {
        .container > div:last-child {
            grid-template-columns: 1fr !important;
        }
        .container > div:last-child > div:last-child {
            position: static !important;
        }
    }
    
    input[type='range']::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        cursor: pointer;
        box-shadow: 0 0 20px rgba(0, 255, 106, 0.5);
    }
    
    input[type='range']::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        cursor: pointer;
        border: none;
        box-shadow: 0 0 20px rgba(0, 255, 106, 0.5);
    }
    
    .duration-btn.active {
        background: var(--primary) !important;
        color: var(--dark) !important;
        border-color: var(--primary) !important;
    }
    
    #local-option:has(input:checked),
    #tourist-option:has(input:checked) {
        border-color: var(--primary) !important;
        background: rgba(0, 255, 106, 0.1) !important;
    }
</style>

<script>
    const durationInput = document.getElementById('duration_minutes');
    const durationDisplay = document.getElementById('duration-display');
    const durationPrice = document.getElementById('duration-price');
    const pricePerHour = {{ $scooter->price_hour }};
    const pricePerMinute = pricePerHour / 60;

    function updateDuration() {
        const minutes = parseInt(durationInput.value);
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;

        let text = '';
        if (hours > 0) text += hours + 'h ';
        if (remainingMinutes > 0) text += remainingMinutes + ' min';
        if (text === '') text = minutes + ' min';
        durationDisplay.textContent = text;

        const price = minutes * pricePerMinute;
        durationPrice.textContent = price.toFixed(2) + ' $';
        
        // Update active duration button
        document.querySelectorAll('.duration-btn').forEach(btn => {
            btn.classList.remove('active');
            if (parseInt(btn.dataset.minutes) === minutes) {
                btn.classList.add('active');
            }
        });
    }

    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const minutes = parseInt(btn.dataset.minutes);
            durationInput.value = minutes;
            updateDuration();
        });
    });

    durationInput.addEventListener('input', updateDuration);
    
    // Initialize
    updateDuration();
    document.querySelector('.duration-btn[data-minutes="30"]').classList.add('active');
</script>
@endsection
