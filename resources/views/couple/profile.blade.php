@extends('layouts.mobile')

@section('title', 'Profile - Couple Planner 💕')

@section('content')
<div class="header">
    <h1>{{ $couple->partner_one_name }} & {{ $couple->partner_two_name }}</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">Your relationship profile</p>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Relationship Overview -->
    <div class="relationship-duration">
        <div class="duration-main">
            {{ $couple->relationship_duration['readable'] }}
        </div>
        <div class="duration-subtitle">
            Since {{ $couple->relationship_start_date->format('M d, Y') }}
        </div>
        <div style="margin-top: 12px; font-size: 12px; color: #666;">
            {{ number_format($couple->relationship_duration['total_days']) }} days • {{ $couple->relationship_duration['months'] }} months • {{ $couple->relationship_duration['years'] }} years
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="card">
        <h2 class="card-title">Relationship Stats 📊</h2>
        <div class="stat-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $couple->datePlans()->count() }}</div>
                <div class="stat-label">Total Dates</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $couple->datePlans()->where('status', 'completed')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $couple->trips()->count() }}</div>
                <div class="stat-label">Trips Planned</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $couple->milestones ? count($couple->milestones) : 0 }}</div>
                <div class="stat-label">Milestones</div>
            </div>
        </div>
    </div>

    <!-- Next Anniversary -->
    <div class="card">
        <h2 class="card-title">Next Anniversary 🎉</h2>
        <div style="text-align: center; padding: 16px;">
            <div style="font-size: 24px; font-weight: 600; color: #ff6b6b; margin-bottom: 8px;">
                {{ $couple->next_anniversary->format('M d, Y') }}
            </div>
            <div style="color: #666;">
                {{ $couple->next_anniversary->diffForHumans() }}
            </div>
            <div style="margin-top: 12px; font-size: 14px;">
                <strong>{{ $couple->next_anniversary->diffInYears($couple->relationship_start_date) }}</strong>
                {{ Str::plural('year', $couple->next_anniversary->diffInYears($couple->relationship_start_date)) }} together! 💕
            </div>
        </div>
    </div>

    <!-- Milestones -->
    <div class="card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 12px;">
            <h2 class="card-title" style="margin: 0;">Milestones 🎯</h2>
            <button onclick="showAddMilestone()" style="background: #667eea; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                + Add
            </button>
        </div>

        @if($couple->milestones && count($couple->milestones) > 0)
            @foreach(collect($couple->milestones)->sortByDesc('date') as $milestone)
                <div class="list-item">
                    <div class="list-icon" style="background: #ffc107; color: white;">
                        🎉
                    </div>
                    <div class="list-content">
                        <div class="list-title">{{ $milestone['title'] }}</div>
                        <div class="list-subtitle">
                            {{ \Carbon\Carbon::parse($milestone['date'])->format('M d, Y') }}
                            • {{ \Carbon\Carbon::parse($milestone['date'])->diffForHumans() }}
                        </div>
                        @if(isset($milestone['description']) && $milestone['description'])
                            <div class="list-subtitle" style="margin-top: 4px; font-style: italic;">
                                {{ $milestone['description'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon">🎯</div>
                <p>No milestones added yet!</p>
                <button onclick="showAddMilestone()" style="color: #667eea; background: none; border: none; cursor: pointer; text-decoration: underline;">
                    Add your first milestone →
                </button>
            </div>
        @endif
    </div>

    <!-- Profile Settings -->
    <div class="card">
        <h2 class="card-title">Profile Settings ⚙️</h2>

        <form method="POST" action="{{ route('couple.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: #333; font-size: 14px;">
                    Partner Names
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <input
                        type="text"
                        name="partner_one_name"
                        value="{{ $couple->partner_one_name }}"
                        required
                        style="padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px;"
                    >
                    <input
                        type="text"
                        name="partner_two_name"
                        value="{{ $couple->partner_two_name }}"
                        required
                        style="padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px;"
                    >
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: #333; font-size: 14px;">
                    Relationship Start Date
                </label>
                <input
                    type="date"
                    name="relationship_start_date"
                    value="{{ $couple->relationship_start_date->format('Y-m-d') }}"
                    required
                    max="{{ date('Y-m-d') }}"
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px;"
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: #333; font-size: 14px;">
                    Anniversary Reminders
                </label>
                <select
                    name="anniversary_reminder"
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px;"
                >
                    <option value="monthly" {{ $couple->anniversary_reminder == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $couple->anniversary_reminder == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="weekly" {{ $couple->anniversary_reminder == 'weekly' ? 'selected' : '' }}>Weekly</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px; color: #333; font-size: 14px;">
                    Relationship Description
                </label>
                <textarea
                    name="relationship_description"
                    rows="2"
                    maxlength="500"
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px; resize: vertical;"
                    placeholder="Describe your relationship..."
                >{{ $couple->relationship_description }}</textarea>
            </div>

            <button
                type="submit"
                style="width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;"
            >
                Save Changes
            </button>
        </form>
    </div>
</div>

<!-- Add Milestone Modal -->
<div id="milestoneModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 20px;">
    <div style="background: white; border-radius: 12px; max-width: 400px; margin: 50px auto; padding: 20px;">
        <h3 style="margin: 0 0 16px 0;">Add Milestone 🎯</h3>

        <form method="POST" action="{{ route('couple.milestone') }}">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px;">Title</label>
                <input
                    type="text"
                    name="milestone_title"
                    required
                    maxlength="255"
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px;"
                    placeholder="First kiss, Moving in together..."
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px;">Date</label>
                <input
                    type="date"
                    name="milestone_date"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 500; margin-bottom: 6px;">Description (optional)</label>
                <textarea
                    name="milestone_description"
                    rows="2"
                    maxlength="255"
                    style="width: 100%; padding: 10px; border: 1px solid #e9ecef; border-radius: 6px; resize: vertical;"
                    placeholder="Additional details..."
                ></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button type="button" onclick="hideAddMilestone()" style="padding: 12px; background: #6c757d; color: white; border: none; border-radius: 6px;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px; background: #ffc107; color: white; border: none; border-radius: 6px; font-weight: 500;">
                    Add Milestone
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddMilestone() {
        document.getElementById('milestoneModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideAddMilestone() {
        document.getElementById('milestoneModal').style.display = 'none';
        document.body.style.overflow = 'auto';

        // Reset form
        const form = document.querySelector('#milestoneModal form');
        form.reset();
    }

    // Close modal when clicking outside
    document.getElementById('milestoneModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideAddMilestone();
        }
    });
</script>
@endsection
