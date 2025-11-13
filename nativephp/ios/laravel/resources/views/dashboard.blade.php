@extends('layouts.mobile')

@section('title', 'Dashboard - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Welcome Back! 💕</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">{{ $couple->partner_one_name }} & {{ $couple->partner_two_name }}</p>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if(isset($waitingForPartner) && $waitingForPartner)
        <!-- Waiting for Partner State -->
        <div class="card">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.7;">⏳</div>
                <h2 style="margin: 0 0 8px 0; color: #333;">Waiting for {{ $couple->partner_two_name }}</h2>
                <p style="margin: 0 0 16px 0; color: #666;">
                    Once {{ $couple->partner_two_name }} joins using your invitation code, you'll both have full access to your couple planner!
                </p>

                <!-- Invitation Code Reminder -->
                <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 16px; border-radius: 8px; margin: 16px 0;">
                    <div style="font-size: 12px; opacity: 0.8; margin-bottom: 4px;">INVITATION CODE</div>
                    <div style="font-size: 24px; font-weight: 700; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                        {{ $couple->invitation_code }}
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 20px;">
                    <a href="{{ route('couple.invitation-code') }}" style="padding: 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; text-align: center;">
                        📨 Share Code
                    </a>
                    <button onclick="location.reload()" style="padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">
                        🔄 Check Status
                    </button>
                </div>
            </div>
        </div>

        <!-- Limited Preview -->
        <div class="card">
            <h2 class="card-title">Preview: What's Coming 👀</h2>
            <div style="opacity: 0.6;">
                <div style="display: grid; gap: 16px;">
                    <div style="padding: 16px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #ff6b6b;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 24px;">💝</div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: 4px;">Date Planning</div>
                                <div style="font-size: 14px; color: #666;">Plan romantic dates together</div>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 16px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #4ecdc4;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 24px;">✈️</div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: 4px;">Trip Planning</div>
                                <div style="font-size: 14px; color: #666;">Plan adventures and getaways</div>
                            </div>
                        </div>
                    </div>

                    <div style="padding: 16px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #45b7d1;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 24px;">📊</div>
                            <div>
                                <div style="font-weight: 500; margin-bottom: 4px;">Relationship Stats</div>
                                <div style="font-size: 14px; color: #666;">Track your journey together</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Full Dashboard (both partners joined) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $relationshipStats['days_together'] }}</div>
                <div class="stat-label">Days Together</div>
                <div class="stat-icon">💕</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $relationshipStats['dates_planned'] }}</div>
                <div class="stat-label">Dates Planned</div>
                <div class="stat-icon">🌹</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $relationshipStats['trips_planned'] }}</div>
                <div class="stat-label">Trips Planned</div>
                <div class="stat-icon">✈️</div>
            </div>
        </div>

        <!-- Upcoming Dates -->
        <div class="card">
            <h2 class="card-title">Upcoming Dates 💝</h2>
            @if($upcomingDates->count() > 0)
                @foreach($upcomingDates as $date)
                    <div class="date-item">
                        <div class="date-info">
                            <div class="date-title">{{ $date->title }}</div>
                            <div class="date-date">{{ $date->planned_date->format('M j, Y') }}</div>
                            <div class="date-location">📍 {{ $date->location }}</div>
                        </div>
                        <div class="date-status status-{{ $date->status }}">{{ ucfirst($date->status) }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">💝</div>
                    <p>No upcoming dates planned yet!</p>
                    <a href="{{ route('dates.create') }}" class="btn-primary">Plan Your First Date</a>
                </div>
            @endif
        </div>

        <!-- Upcoming Trips -->
        <div class="card">
            <h2 class="card-title">Upcoming Trips ✈️</h2>
            @if($upcomingTrips->count() > 0)
                @foreach($upcomingTrips as $trip)
                    <div class="trip-item">
                        <div class="trip-info">
                            <div class="trip-title">{{ $trip->title }}</div>
                            <div class="trip-dates">{{ $trip->start_date->format('M j') }} - {{ $trip->end_date->format('M j, Y') }}</div>
                            <div class="trip-destination">🌍 {{ $trip->destination }}</div>
                        </div>
                        <div class="trip-status status-{{ $trip->status }}">{{ ucfirst($trip->status) }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">✈️</div>
                    <p>No upcoming trips planned yet!</p>
                    <a href="{{ route('trips.create') }}" class="btn-primary">Plan Your First Trip</a>
                </div>
            @endif
        </div>
    @endif

    <!-- Relationship Timeline (always visible) -->
    <div class="card">
        <h2 class="card-title">Your Journey 📅</h2>
        <div class="timeline-item">
            <div class="timeline-icon">💕</div>
            <div class="timeline-content">
                <div class="timeline-title">Relationship Started</div>
                <div class="timeline-date">{{ $couple->relationship_start_date->format('F j, Y') }}</div>
                @if($couple->relationship_description)
                    <div class="timeline-description">{{ $couple->relationship_description }}</div>
                @endif
            </div>
        </div>

        @if(!isset($waitingForPartner) || !$waitingForPartner)
            <div class="timeline-item">
                <div class="timeline-icon">📱</div>
                <div class="timeline-content">
                    <div class="timeline-title">Joined Couple Planner</div>
                    <div class="timeline-date">{{ $couple->created_at->format('F j, Y') }}</div>
                    <div class="timeline-description">Started planning your future together!</div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    .stat-icon {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 16px;
        opacity: 0.7;
    }

    .date-item, .trip-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .date-title, .trip-title {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .date-date, .trip-dates {
        font-size: 14px;
        color: #666;
        margin-bottom: 2px;
    }

    .date-location, .trip-destination {
        font-size: 12px;
        color: #888;
    }

    .date-status, .trip-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-planned { background: #e3f2fd; color: #1976d2; }
    .status-confirmed { background: #e8f5e8; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    .timeline-item {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .timeline-title {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .timeline-date {
        font-size: 14px;
        color: #666;
        margin-bottom: 4px;
    }

    .timeline-description {
        font-size: 14px;
        color: #888;
        font-style: italic;
    }
</style>

<script>
    // Auto-refresh every 30 seconds if waiting for partner
    @if(isset($waitingForPartner) && $waitingForPartner)
        setInterval(() => {
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    // Check if partner has joined by looking for the waiting state
                    if (!html.includes('Waiting for {{ $couple->partner_two_name }}')) {
                        location.reload();
                    }
                })
                .catch(() => {
                    // If fetch fails, just reload
                    location.reload();
                });
        }, 30000);
    @endif
</script>
@endsection
