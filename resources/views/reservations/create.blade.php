@extends('layouts.app')

@section('title', 'Réserver une trottinette - VoltRide')

@section('content')
@include('components.responsive-styles')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #47F55B 0%, #07d65d 100%);
        --primary-dark: #0a9b3a;
        --primary-light: #f0fdf4;
        --text-primary: #0f172a;
        --text-secondary: #4a5568;
    }
    @media (max-width: 767px) {
        .reservation-grid {
            grid-template-columns: 1fr !important;
        }
        .sidebar-pricing {
            position: static !important;
            top: auto !important;
        }
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .slide-in {
        animation: slideIn 0.5s ease-out forwards;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #07d65d !important;
        box-shadow: 0 0 0 3px rgba(7, 214, 93, 0.1) !important;
        outline: none;
    }

    .duration-btn {
        transition: all 0.3s ease;
    }

    .duration-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(31, 117, 80, 0.2);
    }
    
    input[type='range']::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #1f7550;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    input[type='range']::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #1f7550;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
</style>

<div style="max-width: 1200px; margin: 0 auto; padding: 1rem 1.5rem; overflow-x: hidden;">
    <!-- Header -->
    <div style="margin-bottom: 40px; text-align: center;">
        <h1 style="color: #0a9b3a; font-size: clamp(1.5rem, 5vw, 2.5rem); font-weight: 700; margin-bottom: 12px;">
            ⚡ Réserver {{ $scooter->name }}
        </h1>
        <p style="color: #4a5568; font-size: clamp(1rem, 2vw, 1.1rem); margin: 0;">
            La location la plus simple et rapide de la ville
        </p>
    </div>

    <!-- Main Grid -->
    <div class="reservation-grid responsive-grid-2" style="display: grid; grid-template-columns: 1fr 350px; gap: 32px;">
        
        <!-- Form Section -->
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="scooter_id" value="{{ $scooter->id }}">

            <!-- Scooter Card -->
            <div class="card slide-in" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 32px; display: flex; gap: clamp(12px, 3vw, 16px); flex-wrap: wrap; align-items: flex-start; animation-delay: 0.1s;">
                <img src="{{ $scooter->images->first()?->getUrl() ?? 'https://via.placeholder.com/120x120' }}" alt="{{ $scooter->name }}" loading="lazy" style="width: clamp(80px, 20vw, 100px); height: clamp(80px, 20vw, 100px); border-radius: 8px; object-fit: contain; background: #f9f9f9; padding: 8px; flex-shrink: 0;">
                <div style="flex: 1; min-width: 200px;">
                    <h3 style="color: #1f7550; font-weight: 700; font-size: clamp(1.2rem, 4vw, 1.5rem); margin: 0 0 4px 0;">{{ $scooter->name }}</h3>
                    <p style="color: #4a5568; margin: 8px 0; line-height: 1.6; font-size: clamp(0.9rem, 2vw, 1rem);">{{ Str::limit($scooter->description, 150) }}</p>
                    <p style="color: #2d9b6f; font-weight: 700; font-size: clamp(1rem, 2vw, 1.1rem); margin: 0;">🔋 Batterie: {{ $scooter->battery_level }}%</p>
                </div>
            </div>

            <!-- Date & Time Section -->
            <div class="card slide-in" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 24px; animation-delay: 0.2s;">
                <h3 style="color: #1f7550; font-weight: 700; font-size: clamp(1.1rem, 3vw, 1.3rem); margin-bottom: 20px;">📅 Quand?</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label for="start_date" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px; font-size: clamp(0.9rem, 2vw, 1rem);">Date</label>
                        <input type="date" id="start_date" name="start_date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; box-sizing: border-box;" min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}">
                        @error('start_date') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="start_time" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px; font-size: clamp(0.9rem, 2vw, 1rem);">Heure</label>
                        <input type="time" id="start_time" name="start_time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; box-sizing: border-box;" value="{{ old('start_time', '09:00') }}">
                        @error('start_time') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Duration Section -->
            <div class="card slide-in" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 24px; animation-delay: 0.3s;">
                <h3 style="color: #1f7550; font-weight: 700; font-size: clamp(1.1rem, 3vw, 1.3rem); margin-bottom: 20px;">⏱️ Durée (minutes)</h3>
                
                <div style="margin-bottom: 20px;">
                    <input type="range" id="duration_minutes" name="duration_minutes" min="30" max="60" step="5" value="{{ old('duration_minutes', 30) }}" style="width: 100%; height: 8px; border-radius: 5px; background: #e2e8f0; outline: none; -webkit-appearance: none; box-sizing: border-box;"/>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(70px, 1fr)); gap: 8px; margin-bottom: 20px;">
                    <button type="button" class="duration-btn" data-minutes="30" style="padding: 12px; background: #f0f0f0; border: 2px solid #1f7550; color: #1f7550; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: clamp(0.75rem, 2vw, 0.9rem); white-space: nowrap;">30 min</button>
                    <button type="button" class="duration-btn" data-minutes="45" style="padding: 12px; background: #f0f0f0; border: 2px solid #e2e8f0; color: #4a5568; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: clamp(0.75rem, 2vw, 0.9rem); white-space: nowrap;">45 min</button>
                    <button type="button" class="duration-btn" data-minutes="60" style="padding: 12px; background: #f0f0f0; border: 2px solid #e2e8f0; color: #4a5568; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: clamp(0.75rem, 2vw, 0.9rem); white-space: nowrap;">1h</button>
                    <button type="button" class="duration-btn" data-minutes="120" style="padding: 12px; background: #f0f0f0; border: 2px solid #e2e8f0; color: #4a5568; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: clamp(0.75rem, 2vw, 0.9rem); white-space: nowrap;">2h</button>
                </div>

                <div id="duration-display" style="font-size: clamp(1.5rem, 5vw, 2rem); font-weight: 700; color: #1f7550; text-align: center; margin-bottom: 20px;">30 minutes</div>

                <div style="background: #fef3c7; border: 2px solid #f59e0b; padding: 16px; border-radius: 8px; color: #92400e; font-size: clamp(0.85rem, 2vw, 1rem);">
                    <strong>ℹ️ Important:</strong> Seuls les touristes peuvent réserver pour 2 heures.
                </div>

                @error('duration_minutes') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 12px;">{{ $message }}</p> @enderror
            </div>

            <!-- Tourist Selection -->
            <div class="card" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 24px;">
                <h3 style="color: #1f7550; font-weight: 700; font-size: clamp(1.1rem, 3vw, 1.3rem); margin-bottom: 20px;">🌍 Vous êtes?</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <label style="border: 2px solid #e2e8f0; padding: 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; font-size: clamp(0.9rem, 2vw, 1rem);">
                        <input type="radio" name="is_tourist" value="0" {{ old('is_tourist', 0) == 0 ? 'checked' : '' }} style="margin-right: 8px;">
                        <div style="color: #1f7550; font-weight: 600;">👤 Local</div>
                        <p style="color: #999; font-size: 0.85rem; margin: 4px 0 0 0;">Max 2h</p>
                    </label>
                    <label style="border: 2px solid #e2e8f0; padding: 16px; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; font-size: clamp(0.9rem, 2vw, 1rem);">
                        <input type="radio" name="is_tourist" value="1" {{ old('is_tourist') == 1 ? 'checked' : '' }} style="margin-right: 8px;">
                        <div style="color: #1f7550; font-weight: 600;">✈️ Touriste</div>
                        <p style="color: #999; font-size: 0.85rem; margin: 4px 0 0 0;">Jusqu'à 2h</p>
                    </label>
                </div>
            </div>

            <!-- Guest Info Section -->
            <div class="card" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 24px;">
                <h3 style="color: #1f7550; font-weight: 700; font-size: clamp(1.1rem, 3vw, 1.3rem); margin-bottom: 20px;">👤 Vos informations</h3>
                
                <div style="margin-bottom: 16px;">
                    <label for="guest_name" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px; font-size: clamp(0.9rem, 2vw, 1rem);">Nom complet *</label>
                    <input type="text" id="guest_name" name="guest_name" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; box-sizing: border-box;" value="{{ old('guest_name', auth()->user()?->name) }}" placeholder="Jean Dupont">
                    @error('guest_name') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="guest_phone" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px; font-size: clamp(0.9rem, 2vw, 1rem);">Téléphone *</label>
                    <input type="tel" id="guest_phone" name="guest_phone" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; box-sizing: border-box;" value="{{ old('guest_phone') }}" placeholder="+33 6 12 34 56">
                    @error('guest_phone') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="guest_email" style="display: block; color: #1f7550; font-weight: 600; margin-bottom: 8px; font-size: clamp(0.9rem, 2vw, 1rem);">Email *</label>
                    <input type="email" id="guest_email" name="guest_email" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; box-sizing: border-box;" value="{{ old('guest_email', auth()->user()?->email) }}" placeholder="jean@example.com">
                    @error('guest_email') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Terms Acceptance -->
            <div class="card" style="padding: clamp(16px, 4vw, 24px); margin-bottom: 24px;">
                <label style="display: flex; gap: 12px; cursor: pointer; font-size: clamp(0.85rem, 2vw, 1rem);">
                    <input type="checkbox" name="accept_terms" value="1" required style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer; flex-shrink: 0;">
                    <span style="color: #4a5568; line-height: 1.6;">
                        J'accepte les <strong>conditions</strong> included: paiement sur place, responsabilité de la trottinette, caution 50€, assurance incluse
                    </span>
                </label>
                @error('accept_terms') <p style="color: #e53e3e; font-size: 0.85rem; margin-top: 12px;">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" style="width: 100%; padding: clamp(12px, 3vw, 16px); background: linear-gradient(135deg, #47F55B 0%, #07d65d 100%); color: #0f172a; font-weight: 700; font-size: clamp(1rem, 2vw, 1.1rem); border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s; box-sizing: border-box; box-shadow: 0 4px 12px rgba(71, 245, 91, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(71, 245, 91, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(71, 245, 91, 0.3)'">
                ✓ Finaliser
            </button>
        </form>

        <!-- Pricing Sidebar -->
        <div class="sidebar-pricing" style="position: sticky; top: 120px; height: fit-content;">
            <div class="card" style="padding: clamp(16px, 4vw, 24px);">
                <h3 style="color: #0a9b3a; font-weight: 700; font-size: clamp(1rem, 2vw, 1.2rem); margin-bottom: 20px;">💰 Tarifs</h3>
                
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: clamp(0.85rem, 2vw, 1rem);">
                        <span style="color: #4a5568;">À l'heure</span>
                        <span style="font-weight: 700; color: #0a9b3a;">{{ number_format($scooter->price_hour, 2) }}€</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: clamp(0.85rem, 2vw, 1rem);">
                        <span style="color: #4a5568;">À la minute</span>
                        <span style="font-weight: 700; color: #0a9b3a;">{{ number_format($scooter->price_minute, 2) }}€</span>
                    </div>
                    <div style="border-top: 2px solid #e2e8f0; padding-top: 12px; display: flex; justify-content: space-between; font-size: clamp(0.85rem, 2vw, 1rem);">
                        <span style="color: #4a5568; font-weight: 600;">Durée</span>
                        <span id="duration-price" style="font-size: clamp(1.2rem, 3vw, 1.5rem); font-weight: 700; color: #0a9b3a;">{{ number_format((30 * $scooter->price_minute), 2) }}€</span>
                    </div>
                </div>

                <div style="background: #f0fdf4; border-left: 4px solid #1f7550; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: clamp(0.8rem, 2vw, 0.9rem);">
                    <p style="color: #166534; font-weight: 600; margin-bottom: 8px;">💡 Info</p>
                    <p style="color: #166534; margin: 0;">Paiement sur place espèces ou carte</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const durationInput = document.getElementById('duration_minutes');
    const durationDisplay = document.getElementById('duration-display');
    const durationPrice = document.getElementById('duration-price');
    const priceHour = {{ $scooter->price_hour }};
    const priceMinute = {{ $scooter->price_minute }};

    function updateDuration() {
        const minutes = parseInt(durationInput.value);
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;

        let text = '';
        if (hours > 0) text += hours + 'h ';
        if (remainingMinutes > 0) text += remainingMinutes + 'min';
        if (text === '') text = minutes + 'min';
        durationDisplay.textContent = text;

        const price = (hours * priceHour) + (remainingMinutes * priceMinute);
        durationPrice.textContent = price.toFixed(2) + '€';
    }

    document.querySelectorAll('.duration-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const minutes = btn.dataset.minutes;
            durationInput.value = minutes;
            document.querySelectorAll('.duration-btn').forEach(b => {
                b.style.borderColor = '#e2e8f0';
                b.style.color = '#4a5568';
                b.style.background = '#f0f0f0';
            });
            btn.style.borderColor = '#1f7550';
            btn.style.color = '#1f7550';
            btn.style.background = '#eff6f3';
            updateDuration();
        });
    });

    durationInput.addEventListener('input', updateDuration);
    updateDuration();
</script>
@endsection
