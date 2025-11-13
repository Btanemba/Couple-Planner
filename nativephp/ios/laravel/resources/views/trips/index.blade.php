@extends('layouts.mobile')

@section('title', 'Trip Plans - Couple Planner ✈️')

@section('content')
<div class="header">
    <h1>Your Adventures ✈️</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Plan and track your trips together</p>
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

    <!-- Quick Add Button -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('trips.create') }}" class="action-btn" style="display: block; text-align: center; text-decoration: none;">
            <div style="font-size: 20px; margin-bottom: 4px;">✈️</div>
            Plan a New Trip
        </a>
    </div>

    <!-- Upcoming Trips -->
    @if($upcomingTrips->count() > 0)
        <div class="card">
            <h2 class="card-title">Upcoming Adventures ({{ $upcomingTrips->count() }})</h2>
            @foreach($upcomingTrips as $trip)
                <div class="list-item" style="position: relative;">
                    <div class="list-icon" style="background: {{ $trip->status_color }}; color: white;">
                        ✈️
                    </div>
                    <div class="list-content">
                        <div class="list-title">{{ $trip->title }}</div>
                        <div class="list-subtitle">
                            📍 {{ $trip->destination }}
                        </div>
                        <div class="list-subtitle">
                            📅 {{ $trip->start_date->format('M d') }} - {{ $trip->end_date->format('M d, Y') }}
                            ({{ $trip->duration }} {{ Str::plural('day', $trip->duration) }})
                        </div>
                        @if($trip->time_until_trip)
                            <div class="list-subtitle" style="color: #667eea; font-weight: 500; margin-top: 2px;">
                                {{ $trip->time_until_trip }}
                            </div>
                        @endif
                        @if($trip->budget)
                            <div class="list-subtitle" style="margin-top: 4px;">
                                💰 Budget: ${{ number_format($trip->budget, 2) }}
                                @if($trip->spent_amount > 0)
                                    • Spent: ${{ number_format($trip->spent_amount, 2) }}
                                    ({{ number_format($trip->budget_utilization, 1) }}%)
                                @endif
                            </div>
                        @endif
                        @if($trip->accommodation || $trip->transportation)
                            <div class="list-subtitle" style="margin-top: 4px;">
                                @if($trip->accommodation)
                                    🏨 {{ $trip->accommodation }}
                                @endif
                                @if($trip->transportation)
                                    🚗 {{ $trip->transportation }}
                                @endif
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div style="margin-top: 8px;">
                            <span style="background: {{ $trip->status_color }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 10px; text-transform: uppercase;">
                                {{ str_replace('_', ' ', $trip->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        @if($trip->status === 'planning')
                            <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="booked">
                                <button type="submit" style="background: #007bff; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; cursor: pointer;">
                                    📋 Book
                                </button>
                            </form>
                        @elseif($trip->status === 'booked')
                            <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" style="background: #28a745; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; cursor: pointer;">
                                    🚀 Start
                                </button>
                            </form>
                        @elseif($trip->status === 'in_progress')
                            <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" style="background: #6f42c1; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; cursor: pointer;">
                                    ✅ Complete
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('trips.show', $trip) }}" style="background: #17a2b8; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; text-decoration: none; text-align: center;">
                            👁️ View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">✈️</div>
                <h3 style="margin: 0 0 8px 0;">No trips planned yet!</h3>
                <p style="margin: 0 0 16px 0; color: #666;">Start planning your next adventure together</p>
                <a href="{{ route('trips.create') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">
                    Plan your first trip →
                </a>
            </div>
        </div>
    @endif

    <!-- Past Trips -->
    @if($pastTrips->count() > 0)
        <div class="card">
            <h2 class="card-title">Travel Memories 📸</h2>
            @foreach($pastTrips as $trip)
                <div class="list-item">
                    <div class="list-icon" style="background: #28a745; color: white;">
                        ✅
                    </div>
                    <div class="list-content">
                        <div class="list-title">{{ $trip->title }}</div>
                        <div class="list-subtitle">
                            📍 {{ $trip->destination }} • {{ $trip->start_date->format('M Y') }}
                        </div>
                        <div class="list-subtitle" style="margin-top: 2px;">
                            {{ $trip->duration }} {{ Str::plural('day', $trip->duration) }}
                            @if($trip->spent_amount > 0)
                                • Spent ${{ number_format($trip->spent_amount, 2) }}
                            @endif
                        </div>
                        @if($trip->notes)
                            <div class="list-subtitle" style="margin-top: 4px; font-style: italic; color: #28a745;">
                                "{{ Str::limit($trip->notes, 60) }}"
                            </div>
                        @endif
                    </div>
                    <div style="color: #28a745; font-size: 12px; font-weight: 500;">
                        {{ $trip->start_date->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Trip Stats -->
    <div class="card">
        <h2 class="card-title">Travel Stats 📊</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; text-align: center;">
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #667eea;">
                    {{ $upcomingTrips->count() + $pastTrips->count() }}
                </div>
                <div style="font-size: 12px; color: #666;">Total Trips</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #28a745;">
                    {{ $pastTrips->count() }}
                </div>
                <div style="font-size: 12px; color: #666;">Completed</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #ff6b6b;">
                    {{ $upcomingTrips->merge($pastTrips)->sum('duration') }}
                </div>
                <div style="font-size: 12px; color: #666;">Days Traveled</div>
            </div>
        </div>

        @php
            $totalBudget = $upcomingTrips->merge($pastTrips)->sum('budget');
            $totalSpent = $upcomingTrips->merge($pastTrips)->sum('spent_amount');
        @endphp
        @if($totalBudget > 0 || $totalSpent > 0)
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e9ecef;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; text-align: center;">
                    @if($totalBudget > 0)
                        <div>
                            <div style="font-size: 20px; font-weight: 600; color: #17a2b8;">
                                ${{ number_format($totalBudget, 0) }}
                            </div>
                            <div style="font-size: 12px; color: #666;">Total Budget</div>
                        </div>
                    @endif
                    @if($totalSpent > 0)
                        <div>
                            <div style="font-size: 20px; font-weight: 600; color: #fd7e14;">
                                ${{ number_format($totalSpent, 0) }}
                            </div>
                            <div style="font-size: 12px; color: #666;">Total Spent</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Travel Inspiration -->
    <div class="card">
        <h2 class="card-title">Travel Inspiration 🌟</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <div style="padding: 12px; background: #e3f2fd; border-radius: 8px; text-align: center;">
                <div style="font-size: 20px; margin-bottom: 4px;">🏖️</div>
                <div style="font-size: 12px; font-weight: 500;">Beach Getaway</div>
            </div>
            <div style="padding: 12px; background: #f3e5f5; border-radius: 8px; text-align: center;">
                <div style="font-size: 20px; margin-bottom: 4px;">🏔️</div>
                <div style="font-size: 12px; font-weight: 500;">Mountain Adventure</div>
            </div>
            <div style="padding: 12px; background: #e8f5e8; border-radius: 8px; text-align: center;">
                <div style="font-size: 20px; margin-bottom: 4px;">🏙️</div>
                <div style="font-size: 12px; font-weight: 500;">City Break</div>
            </div>
            <div style="padding: 12px; background: #fff3e0; border-radius: 8px; text-align: center;">
                <div style="font-size: 20px; margin-bottom: 4px;">🎪</div>
                <div style="font-size: 12px; font-weight: 500;">Cultural Tour</div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-item {
        position: relative;
        align-items: flex-start;
        padding: 16px 0;
    }

    .list-content {
        flex: 1;
        margin-right: 8px;
    }

    /* Mobile touch targets */
    button, a {
        min-height: 32px;
        touch-action: manipulation;
    }

    /* Smooth animations */
    .list-item {
        transition: background-color 0.2s ease;
    }

    .list-item:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 0 -8px;
        padding: 16px 8px;
    }

    /* Status badges */
    .list-item form button {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection
