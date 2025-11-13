@extends('layouts.mobile')

@section('title', 'Planned Dates - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>Your Dates 💕</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Plan and track your romantic moments</p>
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
        <a href="{{ route('dates.create') }}" class="action-btn" style="display: block; text-align: center; text-decoration: none;">
            <div style="font-size: 20px; margin-bottom: 4px;">💕</div>
            Plan a New Date
        </a>
    </div>

    <!-- Upcoming Dates -->
    @if($upcomingDates->count() > 0)
        <div class="card">
            <h2 class="card-title">Upcoming Dates ({{ $upcomingDates->count() }})</h2>
            @foreach($upcomingDates as $date)
                <div class="list-item" style="position: relative;">
                    <div class="list-icon" style="background: {{ $date->category_color }}; color: white;">
                        @switch($date->category)
                            @case('romantic') 💝 @break
                            @case('adventure') 🏔️ @break
                            @case('casual') 😊 @break
                            @case('special') ⭐ @break
                            @default 📅
                        @endswitch
                    </div>
                    <div class="list-content">
                        <div class="list-title">{{ $date->title }}</div>
                        <div class="list-subtitle">
                            {{ $date->planned_date->format('M d, Y g:i A') }}
                            @if($date->location)
                                • {{ $date->location }}
                            @endif
                        </div>
                        <div class="list-subtitle" style="color: #ff6b6b; font-weight: 500; margin-top: 2px;">
                            {{ $date->time_until_date }}
                        </div>
                        @if($date->estimated_cost)
                            <div class="list-subtitle" style="margin-top: 2px;">
                                Budget: ${{ number_format($date->estimated_cost, 2) }}
                            </div>
                        @endif
                        @if($date->description)
                            <div class="list-subtitle" style="margin-top: 4px; font-style: italic;">
                                {{ Str::limit($date->description, 60) }}
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div style="margin-top: 8px;">
                            <span style="background: {{ $date->status_color }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 10px; text-transform: uppercase;">
                                {{ $date->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        @if($date->status === 'planned' || $date->status === 'confirmed')
                            <form method="POST" action="{{ route('dates.complete', $date) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: #28a745; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; cursor: pointer;">
                                    ✅ Complete
                                </button>
                            </form>
                            <form method="POST" action="{{ route('dates.cancel', $date) }}" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: #dc3545; color: white; border: none; border-radius: 6px; padding: 6px 8px; font-size: 10px; cursor: pointer;" onclick="return confirm('Cancel this date?')">
                                    ❌ Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">💕</div>
                <h3 style="margin: 0 0 8px 0;">No upcoming dates planned!</h3>
                <p style="margin: 0 0 16px 0; color: #666;">Start planning some romantic moments together</p>
                <a href="{{ route('dates.create') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">
                    Plan your first date →
                </a>
            </div>
        </div>
    @endif

    <!-- Past Dates -->
    @if($pastDates->count() > 0)
        <div class="card">
            <h2 class="card-title">Recent Memories 📸</h2>
            @foreach($pastDates as $date)
                <div class="list-item">
                    <div class="list-icon" style="background: #28a745; color: white;">
                        ✅
                    </div>
                    <div class="list-content">
                        <div class="list-title">{{ $date->title }}</div>
                        <div class="list-subtitle">
                            {{ $date->planned_date->format('M d, Y') }}
                            @if($date->location)
                                • {{ $date->location }}
                            @endif
                        </div>
                        @if($date->notes)
                            <div class="list-subtitle" style="margin-top: 4px; font-style: italic; color: #28a745;">
                                "{{ Str::limit($date->notes, 60) }}"
                            </div>
                        @endif
                    </div>
                    <div style="color: #28a745; font-size: 12px; font-weight: 500;">
                        {{ $date->planned_date->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Date Categories Stats -->
    <div class="card">
        <h2 class="card-title">Date Categories 📊</h2>
        <div class="stat-grid">
            @php
                $categories = $upcomingDates->merge($pastDates)->groupBy('category');
            @endphp
            @foreach(['romantic' => '💝', 'adventure' => '🏔️', 'casual' => '😊', 'special' => '⭐'] as $category => $emoji)
                <div class="stat-item">
                    <div style="font-size: 20px; margin-bottom: 4px;">{{ $emoji }}</div>
                    <div class="stat-number">{{ $categories->get($category, collect())->count() }}</div>
                    <div class="stat-label">{{ ucfirst($category) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="card">
        <h2 class="card-title">Your Date Stats 💫</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; text-align: center;">
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #ff6b6b;">
                    {{ $upcomingDates->count() + $pastDates->count() }}
                </div>
                <div style="font-size: 12px; color: #666;">Total Dates</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #28a745;">
                    {{ $pastDates->count() }}
                </div>
                <div style="font-size: 12px; color: #666;">Completed</div>
            </div>
            <div>
                <div style="font-size: 24px; font-weight: 600; color: #667eea;">
                    ${{ number_format($upcomingDates->merge($pastDates)->sum('estimated_cost'), 0) }}
                </div>
                <div style="font-size: 12px; color: #666;">Total Budget</div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-item {
        position: relative;
        align-items: flex-start;
    }

    .list-content {
        flex: 1;
        margin-right: 8px;
    }

    /* Mobile touch targets */
    button {
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
        padding: 12px 8px;
    }
</style>
@endsection
