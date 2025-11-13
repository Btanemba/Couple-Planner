@extends('layouts.mobile')

@section('title', 'Plan a Trip - Couple Planner ✈️')

@section('content')
<div class="header">
    <h1>Plan a New Trip ✈️</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Plan your next adventure together</p>
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

    <form method="POST" action="{{ route('trips.store') }}">
        @csrf

        <div class="card">
            <h2 class="card-title">Trip Details ✨</h2>

            <!-- Trip Title -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Trip Name 🏷️
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Weekend in Paris"
                >
            </div>

            <!-- Destination -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Destination 📍
                </label>
                <input
                    type="text"
                    name="destination"
                    value="{{ old('destination') }}"
                    required
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Paris, France"
                >
            </div>

            <!-- Dates -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                        Start Date 📅
                    </label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                        required
                        min="{{ now()->addDay()->format('Y-m-d') }}"
                        style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                        onchange="updateEndDateMin()"
                    >
                </div>
                <div>
                    <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                        End Date 📅
                    </label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        required
                        style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                        onchange="calculateDuration()"
                    >
                </div>
            </div>

            <!-- Duration Display -->
            <div id="durationDisplay" style="margin-bottom: 20px; padding: 8px 12px; background: #e3f2fd; border-radius: 6px; font-size: 14px; color: #1976d2; display: none;">
                <strong>Duration:</strong> <span id="durationText">0 days</span>
            </div>

            <!-- Budget -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Budget 💰
                </label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666;">$</span>
                    <input
                        type="number"
                        name="budget"
                        value="{{ old('budget') }}"
                        min="0"
                        step="0.01"
                        style="width: 100%; padding: 12px 12px 12px 24px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                        placeholder="1000.00"
                    >
                </div>
                <div style="font-size: 12px; color: #666; margin-top: 4px;">
                    Optional - helps track expenses during your trip
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Trip Description 📝
                </label>
                <textarea
                    name="description"
                    rows="3"
                    maxlength="500"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa; resize: vertical;"
                    placeholder="Romantic getaway to explore the city of lights..."
                >{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Travel Details 🚗</h2>

            <!-- Accommodation -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Accommodation 🏨
                </label>
                <input
                    type="text"
                    name="accommodation"
                    value="{{ old('accommodation') }}"
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Hotel Romantique, Airbnb..."
                >
            </div>

            <!-- Transportation -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Transportation 🚗
                </label>
                <input
                    type="text"
                    name="transportation"
                    value="{{ old('transportation') }}"
                    maxlength="255"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Flight, Car rental, Train..."
                >
            </div>

            <!-- Notes -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Special Notes 📋
                </label>
                <textarea
                    name="notes"
                    rows="2"
                    maxlength="500"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa; resize: vertical;"
                    placeholder="Things to remember, reservations needed, etc..."
                >{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
            <a
                href="{{ route('trips.index') }}"
                style="padding: 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; text-align: center; font-weight: 500;"
            >
                Cancel
            </a>
            <button
                type="submit"
                style="padding: 16px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'"
                onmouseout="this.style.transform='translateY(0)'"
            >
                Plan This Trip! ✈️
            </button>
        </div>
    </form>

    <!-- Trip Ideas -->
    <div class="card">
        <h2 class="card-title">Trip Ideas 💡</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <button type="button" onclick="fillTripIdea('Romantic Beach Getaway', 'Maldives', 7)" class="idea-btn">
                🏖️ Beach escape
            </button>
            <button type="button" onclick="fillTripIdea('City Adventure', 'New York City', 4)" class="idea-btn">
                🏙️ City break
            </button>
            <button type="button" onclick="fillTripIdea('Mountain Retreat', 'Swiss Alps', 5)" class="idea-btn">
                🏔️ Mountains
            </button>
            <button type="button" onclick="fillTripIdea('Cultural Journey', 'Tokyo, Japan', 10)" class="idea-btn">
                🎌 Culture
            </button>
            <button type="button" onclick="fillTripIdea('Road Trip Adventure', 'California Coast', 12)" class="idea-btn">
                🚗 Road trip
            </button>
            <button type="button" onclick="fillTripIdea('Island Paradise', 'Santorini', 6)" class="idea-btn">
                🏝️ Island
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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Prevent zoom on iOS form inputs */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        select, textarea, input[type="text"], input[type="date"], input[type="number"] {
            font-size: 16px;
        }
    }
</style>

<script>
    function updateEndDateMin() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDateInput = document.querySelector('input[name="end_date"]');

        if (startDate) {
            endDateInput.min = startDate;

            // If end date is before start date, update it
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        }

        calculateDuration();
    }

    function calculateDuration() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        const durationDisplay = document.getElementById('durationDisplay');
        const durationText = document.getElementById('durationText');

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const timeDiff = end.getTime() - start.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 to include both start and end days

            if (daysDiff > 0) {
                durationText.textContent = daysDiff + (daysDiff === 1 ? ' day' : ' days');
                durationDisplay.style.display = 'block';
            } else {
                durationDisplay.style.display = 'none';
            }
        } else {
            durationDisplay.style.display = 'none';
        }
    }

    function fillTripIdea(title, destination, days) {
        document.querySelector('input[name="title"]').value = title;
        document.querySelector('input[name="destination"]').value = destination;

        // Set dates
        const today = new Date();
        const startDate = new Date(today);
        startDate.setDate(today.getDate() + 30); // 30 days from now

        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + days - 1);

        document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
        document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];

        updateEndDateMin();

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Set default start date to next month
        const startDateInput = document.querySelector('input[name="start_date"]');
        if (!startDateInput.value) {
            const nextMonth = new Date();
            nextMonth.setMonth(nextMonth.getMonth() + 1);
            startDateInput.value = nextMonth.toISOString().split('T')[0];
        }

        updateEndDateMin();
    });
</script>
@endsection
