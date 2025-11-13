@extends('layouts.mobile')

@section('title', 'Join Couple - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Join Your Partner 💕</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Enter your invitation code</p>
</div>

<div class="content">
    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="font-size: 48px; margin-bottom: 16px;">💌</div>
            <h2 style="margin: 0 0 8px 0; color: #333;">Welcome!</h2>
            <p style="margin: 0; color: #666; font-size: 14px;">
                Your partner sent you an invitation code to join your shared couple planner
            </p>
        </div>

        <form action="{{ route('couple.join') }}" method="POST">
            @csrf

            <!-- Invitation Code Input -->
            <div style="margin-bottom: 24px;">
                <label for="invitation_code" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">
                    Invitation Code
                </label>
                <input
                    type="text"
                    id="invitation_code"
                    name="invitation_code"
                    value="{{ old('invitation_code') }}"
                    placeholder="Enter 6-digit code"
                    maxlength="6"
                    style="width: 100%; padding: 16px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 18px; text-align: center; letter-spacing: 4px; font-family: 'Courier New', monospace; text-transform: uppercase;"
                    required
                    autocomplete="off"
                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                >
                <div style="font-size: 12px; color: #666; margin-top: 6px; text-align: center;">
                    6-character code from your partner
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary" style="width: 100%; margin-bottom: 20px; font-size: 16px; padding: 16px;">
                💕 Join Couple
            </button>
        </form>
    </div>

    <!-- Help Card -->
    <div class="card">
        <h2 class="card-title">Need Help? 🤔</h2>
        <div style="display: grid; gap: 16px;">
            <div style="padding: 16px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-weight: 500; margin-bottom: 8px; color: #333;">Can't find your invitation code?</div>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.5; color: #666;">
                    <li>Check your text messages or WhatsApp</li>
                    <li>Look in your email inbox</li>
                    <li>Ask your partner to show you the code again</li>
                </ul>
            </div>

            <div style="padding: 16px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px;">
                <div style="font-weight: 500; margin-bottom: 8px; color: #333;">Code not working?</div>
                <ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.5; color: #666;">
                    <li>Make sure you entered it exactly as shown</li>
                    <li>Codes are case-sensitive (use CAPITAL letters)</li>
                    <li>Check if the code might have expired</li>
                    <li>Ask your partner to generate a new code</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Example Card -->
    <div class="card">
        <h2 class="card-title">Example Code Format 📋</h2>
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 24px; border-radius: 12px; margin-bottom: 16px;">
                <div style="font-size: 12px; opacity: 0.8; margin-bottom: 8px;">EXAMPLE</div>
                <div style="font-size: 32px; font-weight: 700; letter-spacing: 6px; font-family: 'Courier New', monospace;">
                    ABC123
                </div>
            </div>
            <p style="font-size: 14px; color: #666; margin: 0;">
                Your invitation code will look similar to this - a combination of 6 letters and numbers
            </p>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div style="display: grid; gap: 12px; margin-bottom: 40px;">
        <a href="{{ route('couple.setup') }}" style="padding: 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; text-align: center;">
            ← Back to Setup Options
        </a>

        <div style="text-align: center; padding: 16px; color: #666; font-size: 14px;">
            Don't have the app yet?
            <br>
            <strong>Download Couple Planner</strong> and create your account first
        </div>
    </div>
</div>

<style>
    /* Override bottom nav for join page */
    .bottom-nav {
        display: none;
    }

    /* Code input styling */
    #invitation_code:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Auto-format animation */
    @keyframes codeEntered {
        0% { background-color: #e8f5e8; }
        100% { background-color: white; }
    }

    #invitation_code[value]:not([value=""]) {
        animation: codeEntered 0.5s ease-out;
    }
</style>

<script>
    // Auto-submit when 6 characters are entered
    document.getElementById('invitation_code').addEventListener('input', function(e) {
        if (e.target.value.length === 6) {
            // Brief delay to show complete code before submitting
            setTimeout(() => {
                e.target.closest('form').submit();
            }, 500);
        }
    });

    // Focus on load
    window.addEventListener('load', function() {
        document.getElementById('invitation_code').focus();
    });
</script>
@endsection
