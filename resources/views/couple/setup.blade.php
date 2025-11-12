@extends('layouts.mobile')

@section('title', 'Welcome - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Welcome to Couple Planner! 💕</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Let's set up your relationship profile</p>
</div>

<div class="content">
    <div class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="font-size: 64px; margin-bottom: 16px;">👫</div>
            <h2 style="margin: 0 0 8px 0; color: #333;">Tell us about yourselves</h2>
            <p style="margin: 0; color: #666; font-size: 14px;">
                We'll help you plan amazing dates and trips together!
            </p>
        </div>

        <form method="POST" action="{{ route('couple.create') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    First Partner's Name
                </label>
                <input
                    type="text"
                    name="partner_one_name"
                    value="{{ old('partner_one_name') }}"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Your name"
                >
                @error('partner_one_name')
                    <div style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Second Partner's Name
                </label>
                <input
                    type="text"
                    name="partner_two_name"
                    value="{{ old('partner_two_name') }}"
                    required
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                    placeholder="Partner's name"
                >
                @error('partner_two_name')
                    <div style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    When did your relationship start? 💕
                </label>
                <input
                    type="date"
                    name="relationship_start_date"
                    value="{{ old('relationship_start_date') }}"
                    required
                    max="{{ date('Y-m-d') }}"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                >
                @error('relationship_start_date')
                    <div style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Anniversary Reminders
                </label>
                <select
                    name="anniversary_reminder"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa;"
                >
                    <option value="monthly" {{ old('anniversary_reminder') == 'monthly' ? 'selected' : '' }}>
                        Monthly (Every month on your anniversary date)
                    </option>
                    <option value="yearly" {{ old('anniversary_reminder') == 'yearly' ? 'selected' : '' }}>
                        Yearly (Only on your anniversary)
                    </option>
                    <option value="weekly" {{ old('anniversary_reminder') == 'weekly' ? 'selected' : '' }}>
                        Weekly (Every week on your anniversary day)
                    </option>
                </select>
                @error('anniversary_reminder')
                    <div style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 500; margin-bottom: 8px; color: #333;">
                    Describe your relationship (optional) 💭
                </label>
                <textarea
                    name="relationship_description"
                    rows="3"
                    maxlength="500"
                    style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; background: #f8f9fa; resize: vertical;"
                    placeholder="Tell us about your love story..."
                >{{ old('relationship_description') }}</textarea>
                @error('relationship_description')
                    <div style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <button
                type="submit"
                style="width: 100%; padding: 16px; background: linear-gradient(135deg, #ff6b6b, #ff8e8e); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'"
                onmouseout="this.style.transform='translateY(0)'"
            >
                Start Planning Together! 💕
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 24px; padding: 16px; color: #666; font-size: 14px;">
        <p style="margin: 0;">
            🔒 Your information is stored locally and privately<br>
            ✨ Start planning amazing dates and trips together!
        </p>
    </div>
</div>

<style>
    /* Override bottom nav for setup page */
    .bottom-nav {
        display: none;
    }

    /* Mobile form optimizations */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5);
        cursor: pointer;
    }

    /* Focus states for better mobile UX */
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #ff6b6b;
        box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
    }

    /* Prevent zoom on iOS form inputs */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
        select, textarea, input[type="text"], input[type="date"] {
            font-size: 16px;
        }
    }
</style>

<script>
    // Calculate relationship duration preview
    document.querySelector('input[name="relationship_start_date"]').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const now = new Date();
        const diffTime = Math.abs(now - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const years = Math.floor(diffDays / 365);
        const months = Math.floor((diffDays % 365) / 30);

        // Could add a preview here if desired
    });
</script>
@endsection
