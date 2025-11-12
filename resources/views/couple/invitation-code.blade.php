@extends('layouts.mobile')

@section('title', 'Invitation Code - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Your Invitation Code 📨</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Share this with your partner</p>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="font-size: 48px; margin-bottom: 16px;">💌</div>
            <h2 style="margin: 0 0 8px 0; color: #333;">Couple Created Successfully!</h2>
            <p style="margin: 0; color: #666; font-size: 14px;">
                Share this invitation code with {{ $couple->partner_two_name }}
            </p>
        </div>

        <!-- Invitation Code Display -->
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 32px; border-radius: 16px; text-align: center; margin-bottom: 24px;">
            <div style="font-size: 12px; opacity: 0.8; margin-bottom: 8px;">INVITATION CODE</div>
            <div style="font-size: 48px; font-weight: 700; letter-spacing: 8px; font-family: 'Courier New', monospace; margin-bottom: 8px;">
                {{ $couple->invitation_code }}
            </div>
            <div style="font-size: 12px; opacity: 0.8;">Share this 6-digit code</div>
        </div>

        <!-- Share Instructions -->
        <div style="background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 12px 0; color: #333; font-size: 16px;">How to share:</h3>
            <div style="display: grid; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">📱</div>
                    <div>
                        <div style="font-weight: 500; margin-bottom: 2px;">Text Message</div>
                        <div style="font-size: 12px; color: #666;">Send the code via SMS or WhatsApp</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; background: #1da1f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">📧</div>
                    <div>
                        <div style="font-weight: 500; margin-bottom: 2px;">Email</div>
                        <div style="font-size: 12px; color: #666;">Send via email with instructions</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; background: #ff6b6b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">💬</div>
                    <div>
                        <div style="font-weight: 500; margin-bottom: 2px;">In Person</div>
                        <div style="font-size: 12px; color: #666;">Tell them the code directly</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Share Message -->
        <div style="background: #ffe6e6; border: 1px solid #ffbaba; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
            <div style="font-weight: 500; margin-bottom: 8px; color: #333;">Quick Message to Copy:</div>
            <div style="background: white; padding: 12px; border-radius: 6px; font-style: italic; font-size: 14px; line-height: 1.4;">
                "Hey! I set up our couple planner app 💕 Download it and use invitation code: <strong>{{ $couple->invitation_code }}</strong> to join me! We can plan dates and trips together! 😊"
            </div>
            <button onclick="copyMessage()" style="margin-top: 8px; padding: 6px 12px; background: #ff6b6b; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">
                Copy Message
            </button>
        </div>

        <!-- App Download Instructions -->
        <div style="background: #e8f5e8; border: 1px solid #c3e6cb; border-radius: 8px; padding: 16px; margin-bottom: 20px;">
            <div style="font-weight: 500; margin-bottom: 8px; color: #333;">Instructions for {{ $couple->partner_two_name }}:</div>
            <ol style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.5;">
                <li>Download and install the Couple Planner app</li>
                <li>Create an account with their email</li>
                <li>Choose "Join Existing Couple"</li>
                <li>Enter the invitation code: <strong>{{ $couple->invitation_code }}</strong></li>
                <li>Start planning together! 🎉</li>
            </ol>
        </div>
    </div>

    <!-- Status Card -->
    <div class="card">
        <h2 class="card-title">Waiting for {{ $couple->partner_two_name }} 👫</h2>
        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.7;">⏳</div>
            <p style="margin: 0 0 16px 0; color: #666;">
                Once {{ $couple->partner_two_name }} joins using the invitation code, you'll both have access to your shared couple planner!
            </p>
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 12px; font-size: 14px;">
                <strong>Note:</strong> This page will automatically update when they join. You can also refresh to check status.
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
        <button onclick="location.reload()" style="padding: 12px; background: #17a2b8; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">
            🔄 Check Status
        </button>
        <a href="{{ route('dashboard') }}" style="padding: 12px; background: #28a745; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; text-align: center;">
            📱 Go to Dashboard
        </a>
    </div>
</div>

<style>
    /* Override bottom nav for invitation page */
    .bottom-nav {
        display: none;
    }

    /* Pulse animation for waiting status */
    @keyframes pulse {
        0% { opacity: 0.7; }
        50% { opacity: 1; }
        100% { opacity: 0.7; }
    }

    .card:last-of-type .empty-icon {
        animation: pulse 2s infinite;
    }
</style>

<script>
    function copyMessage() {
        const message = `Hey! I set up our couple planner app 💕 Download it and use invitation code: {{ $couple->invitation_code }} to join me! We can plan dates and trips together! 😊`;

        if (navigator.clipboard) {
            navigator.clipboard.writeText(message).then(() => {
                alert('Message copied to clipboard!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = message;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Message copied to clipboard!');
        }
    }

    // Auto-refresh every 30 seconds to check if partner joined
    setInterval(() => {
        location.reload();
    }, 30000);
</script>
@endsection
