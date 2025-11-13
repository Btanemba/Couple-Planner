@extends('layouts.mobile')

@section('title', 'Plan a Date - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Plan a New Date 💕</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Create a special moment together</p>
</div>

<div class="content">
    @if($errors->any())
        <div class="alert" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; margin-bottom: 16px;">
            <strong>Please fix the following:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dates.store') }}">
        @csrf

        <div class="card">
            <h2 class="card-title">Date Details ✨</h2>

            <!-- Date Title -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    What's the plan? 💭
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Dinner at that Italian place"
                >
            </div>

            <!-- Date & Time -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    When? 📅
                </label>
                <input
                    type="datetime-local"
                    name="planned_date"
                    value="{{ old('planned_date') }}"
                    required
                    min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                >
            </div>

            <!-- Location -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Where? 📍
                </label>
                <input
                    type="text"
                    name="location"
                    value="{{ old('location') }}"
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Restaurant name, park, your place..."
                >
            </div>

            <!-- Category -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    What type of date? 💫
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; cursor: pointer; background: #f8f9fa;" onclick="selectCategory(this)">
                        <input type="radio" name="category" value="romantic" {{ old('category') == 'romantic' ? 'checked' : '' }} style="margin-right: 8px;">
                        <span>💝 Romantic</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; cursor: pointer; background: #f8f9fa;" onclick="selectCategory(this)">
                        <input type="radio" name="category" value="adventure" {{ old('category') == 'adventure' ? 'checked' : '' }} style="margin-right: 8px;">
                        <span>🏔️ Adventure</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; cursor: pointer; background: #f8f9fa;" onclick="selectCategory(this)">
                        <input type="radio" name="category" value="casual" {{ old('category') == 'casual' ? 'checked' : '' }} style="margin-right: 8px;">
                        <span>😊 Casual</span>
                    </label>
                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; cursor: pointer; background: #f8f9fa;" onclick="selectCategory(this)">
                        <input type="radio" name="category" value="special" {{ old('category') == 'special' ? 'checked' : '' }} style="margin-right: 8px;">
                        <span>⭐ Special</span>
                    </label>
                </div>
            </div>

            <!-- Estimated Cost -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Estimated Cost 💰
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666;">$</span>
                    <input
                        type="number"
                        name="estimated_cost"
                        value="{{ old('estimated_cost') }}"
                        min="0"
                        step="0.01"
                        style="width: 100%; padding: 12px 12px 12px 24px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                        placeholder="0.00"
                    >
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Special Details 📝
                </label>
                <textarea
                    name="description"
                    rows="3"
                    maxlength="500"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa; resize: vertical;"
                    placeholder="Any special plans, reservations, or things to remember..."
                >{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
            <a
                href="{{ route('dates.index') }}"
                style="padding: 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 500;"
            >
                Cancel
            </a>
            <button
                type="submit"
                style="padding: 16px; background: linear-gradient(135deg, #ff6b6b, #ff8e8e); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'"
                onmouseout="this.style.transform='translateY(0)'"
            >
                Plan This Date! 💕
            </button>
        </div>
    </form>

    <!-- Date Ideas -->
    <div class="card">
        <h2 class="card-title">Need Ideas? 💡</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <button type="button" onclick="fillIdea('Cozy dinner at home', 'romantic', 'Home')" class="idea-btn">
                🏠 Home dinner
            </button>
            <button type="button" onclick="fillIdea('Movie night', 'casual', 'Cinema or home')" class="idea-btn">
                🎬 Movie night
            </button>
            <button type="button" onclick="fillIdea('Hiking adventure', 'adventure', 'Local trail')" class="idea-btn">
                🥾 Hiking
            </button>
            <button type="button" onclick="fillIdea('Coffee date', 'casual', 'Local café')" class="idea-btn">
                ☕ Coffee
            </button>
            <button type="button" onclick="fillIdea('Picnic in the park', 'romantic', 'City park')" class="idea-btn">
                🧺 Picnic
            </button>
            <button type="button" onclick="fillIdea('Game night', 'casual', 'Home')" class="idea-btn">
                🎲 Games
            </button>
        </div>
    </div>
</div>

<style>
    .idea-btn {
        padding: 8px 12px;
        background: #e9ecef;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .idea-btn:hover {
        background: #667eea;
        color: white;
        transform: translateY(-1px);
    }

    /* Focus states for better mobile UX */
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #ff6b6b;
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
    }

    /* Category selection */
    input[type="radio"]:checked + span {
        font-weight: 600;
    }

    label.selected {
        border-color: #ff6b6b !important;
        background: #ffe6e6 !important;
    }

    /* Prevent zoom on iOS form inputs */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        select, textarea, input[type="text"], input[type="datetime-local"], input[type="number"] {
            font-size: 16px;
        }
    }
</style>

<script>
    function selectCategory(label) {
        // Remove selected class from all labels
        document.querySelectorAll('label').forEach(l => l.classList.remove('selected'));
        // Add selected class to clicked label
        label.classList.add('selected');
    }

    function fillIdea(title, category, location) {
        document.querySelector('input[name="title"]').value = title;
        document.querySelector('input[name="location"]').value = location;
        document.querySelector(`input[name="category"][value="${category}"]`).checked = true;

        // Update UI
        document.querySelectorAll('label').forEach(l => l.classList.remove('selected'));
        document.querySelector(`input[name="category"][value="${category}"]`).parentElement.classList.add('selected');

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Set default date to tomorrow evening
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="planned_date"]');
        if (!dateInput.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(19, 0); // 7 PM
            dateInput.value = tomorrow.toISOString().slice(0, 16);
        }

        // Set default category if none selected
        const categoryInputs = document.querySelectorAll('input[name="category"]');
        const isAnyChecked = Array.from(categoryInputs).some(input => input.checked);
        if (!isAnyChecked) {
            categoryInputs[0].checked = true; // Select romantic by default
            categoryInputs[0].parentElement.classList.add('selected');
        }
    });
</script>
@endsection
