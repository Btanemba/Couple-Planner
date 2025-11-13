@extends('layouts.mobile')

@section('title', $trip->title . ' - Trip Details ✈️')

@section('content')
<div class="header">
    <h1>{{ $trip->title }}</h1>
    <p style="margin: 8px 0 0 0; opacity: 0.9;">{{ $trip->destination }}</p>
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

    <!-- Trip Overview -->
    <div class="card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 16px;">
            <h2 class="card-title" style="margin: 0;">Trip Overview</h2>
            <span style="background: {{ $trip->status_color }}; color: white; padding: 6px 12px; border-radius: 12px; font-size: 12px; text-transform: uppercase;">
                {{ str_replace('_', ' ', $trip->status) }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">📅 DATES</div>
                <div style="font-weight: 500;">
                    {{ $trip->start_date->format('M d') }} - {{ $trip->end_date->format('M d, Y') }}
                </div>
                <div style="font-size: 12px; color: #666;">
                    {{ $trip->duration }} {{ Str::plural('day', $trip->duration) }}
                </div>
            </div>
            <div>
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">📍 DESTINATION</div>
                <div style="font-weight: 500;">{{ $trip->destination }}</div>
                @if($trip->time_until_trip)
                    <div style="font-size: 12px; color: #667eea; font-weight: 500;">
                        {{ $trip->time_until_trip }}
                    </div>
                @endif
            </div>
        </div>

        @if($trip->description)
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e9ecef;">
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">✨ DESCRIPTION</div>
                <div style="font-style: italic;">{{ $trip->description }}</div>
            </div>
        @endif
    </div>

    <!-- Budget Overview -->
    @if($trip->budget || $trip->spent_amount > 0)
        <div class="card">
            <h2 class="card-title">Budget Overview 💰</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; text-align: center; margin-bottom: 16px;">
                @if($trip->budget)
                    <div>
                        <div style="font-size: 20px; font-weight: 600; color: #17a2b8;">
                            ${{ number_format($trip->budget, 0) }}
                        </div>
                        <div style="font-size: 12px; color: #666;">Budget</div>
                    </div>
                @endif
                <div>
                    <div style="font-size: 20px; font-weight: 600; color: #fd7e14;">
                        ${{ number_format($trip->spent_amount, 0) }}
                    </div>
                    <div style="font-size: 12px; color: #666;">Spent</div>
                </div>
                @if($trip->budget)
                    <div>
                        <div style="font-size: 20px; font-weight: 600; color: {{ $trip->remaining_budget >= 0 ? '#28a745' : '#dc3545' }};">
                            ${{ number_format($trip->remaining_budget, 0) }}
                        </div>
                        <div style="font-size: 12px; color: #666;">Remaining</div>
                    </div>
                @endif
            </div>

            @if($trip->budget && $trip->spent_amount > 0)
                <!-- Budget Progress Bar -->
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 4px;">
                        <span style="font-size: 12px; color: #666;">Budget Used</span>
                        <span style="font-size: 12px; font-weight: 500;">{{ number_format($trip->budget_utilization, 1) }}%</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; background: {{ $trip->budget_utilization > 100 ? '#dc3545' : ($trip->budget_utilization > 80 ? '#fd7e14' : '#28a745') }}; width: {{ min($trip->budget_utilization, 100) }}%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            @endif

            <!-- Add Expense Form -->
            <form method="POST" action="{{ route('trips.expense', $trip) }}" style="display: flex; gap: 8px;">
                @csrf
                <div style="flex: 1; position: relative;">
                    <span style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: #666; font-size: 14px;">$</span>
                    <input
                        type="number"
                        name="amount"
                        min="0"
                        step="0.01"
                        required
                        style="width: 100%; padding: 8px 8px 8px 20px; border: 1px solid #e9ecef; border-radius: 6px; font-size: 14px;"
                        placeholder="0.00"
                    >
                </div>
                <button type="submit" style="padding: 8px 16px; background: #fd7e14; color: white; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
                    Add Expense
                </button>
            </form>
        </div>
    @endif

    <!-- Travel Details -->
    <div class="card">
        <h2 class="card-title">Travel Details 🚗</h2>

        @if($trip->accommodation)
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">🏨 ACCOMMODATION</div>
                <div style="font-weight: 500;">{{ $trip->accommodation }}</div>
            </div>
        @endif

        @if($trip->transportation)
            <div style="margin-bottom: 16px;">
                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">🚗 TRANSPORTATION</div>
                <div style="font-weight: 500;">{{ $trip->transportation }}</div>
            </div>
        @endif

        @if(!$trip->accommodation && !$trip->transportation)
            <div style="text-align: center; color: #666; font-style: italic;">
                No travel details added yet
            </div>
        @endif
    </div>

    <!-- Itinerary -->
    <div class="card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 12px;">
            <h2 class="card-title" style="margin: 0;">Itinerary 📅</h2>
            <button onclick="showItineraryForm()" style="background: #667eea; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                {{ $trip->itinerary && count($trip->itinerary) > 0 ? 'Edit' : '+ Add' }}
            </button>
        </div>

        @if($trip->itinerary && count($trip->itinerary) > 0)
            @foreach($trip->itinerary as $day)
                <div style="margin-bottom: 16px; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-weight: 500; color: #333; margin-bottom: 4px;">{{ $day['day'] }}</div>
                    <div style="font-size: 14px; color: #666;">{{ $day['activities'] }}</div>
                </div>
            @endforeach
        @else
            <div style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                No itinerary planned yet
            </div>
        @endif
    </div>

    <!-- Packing List -->
    <div class="card">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 12px;">
            <h2 class="card-title" style="margin: 0;">Packing List 🧳</h2>
            <button onclick="showPackingForm()" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                {{ $trip->packing_list && count($trip->packing_list) > 0 ? 'Edit' : '+ Add' }}
            </button>
        </div>

        @if($trip->packing_list && count($trip->packing_list) > 0)
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                @foreach($trip->packing_list as $item)
                    <div style="padding: 8px 12px; background: #e8f5e8; border-radius: 6px; font-size: 14px;">
                        ✓ {{ $item }}
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                No packing list created yet
            </div>
        @endif
    </div>

    <!-- Notes -->
    @if($trip->notes)
        <div class="card">
            <h2 class="card-title">Notes 📋</h2>
            <div style="background: #fffbf0; border: 1px solid #ffeaa7; border-radius: 8px; padding: 12px; font-style: italic;">
                {{ $trip->notes }}
            </div>
        </div>
    @endif

    <!-- Status Actions -->
    <div class="card">
        <h2 class="card-title">Trip Status 🚀</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            @if($trip->status === 'planning')
                <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="booked">
                    <button type="submit" style="width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 6px; font-weight: 500;">
                        📋 Mark as Booked
                    </button>
                </form>
            @elseif($trip->status === 'booked')
                <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" style="width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 500;">
                        🚀 Start Trip
                    </button>
                </form>
            @elseif($trip->status === 'in_progress')
                <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" style="width: 100%; padding: 12px; background: #6f42c1; color: white; border: none; border-radius: 6px; font-weight: 500;">
                        ✅ Complete Trip
                    </button>
                </form>
            @endif

            @if($trip->status !== 'completed' && $trip->status !== 'cancelled')
                <form method="POST" action="{{ route('trips.status', $trip) }}" style="margin: 0;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" style="width: 100%; padding: 12px; background: #dc3545; color: white; border: none; border-radius: 6px; font-weight: 500;" onclick="return confirm('Cancel this trip?')">
                        ❌ Cancel Trip
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('trips.index') }}" style="display: block; padding: 12px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 500;">
            ← Back to Trips
        </a>
    </div>
</div>

<!-- Itinerary Modal -->
<div id="itineraryModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 20px; overflow-y: auto;">
    <div style="background: white; border-radius: 12px; max-width: 400px; margin: 20px auto; padding: 20px;">
        <h3 style="margin: 0 0 16px 0;">Plan Your Itinerary 📅</h3>

        <form method="PUT" action="{{ route('trips.itinerary', $trip) }}">
            @csrf
            @method('PUT')

            <div id="itineraryItems">
                @if($trip->itinerary && count($trip->itinerary) > 0)
                    @foreach($trip->itinerary as $index => $day)
                        <div class="itinerary-item" style="margin-bottom: 16px; padding: 12px; border: 1px solid #e9ecef; border-radius: 8px;">
                            <input type="text" name="itinerary[{{ $index }}][day]" value="{{ $day['day'] }}" placeholder="Day 1" style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; margin-bottom: 8px;">
                            <textarea name="itinerary[{{ $index }}][activities]" rows="2" placeholder="Activities for this day..." style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; resize: vertical;">{{ $day['activities'] }}</textarea>
                            <button type="button" onclick="removeItineraryItem(this)" style="margin-top: 8px; padding: 4px 8px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px;">Remove</button>
                        </div>
                    @endforeach
                @else
                    <div class="itinerary-item" style="margin-bottom: 16px; padding: 12px; border: 1px solid #e9ecef; border-radius: 8px;">
                        <input type="text" name="itinerary[0][day]" placeholder="Day 1" style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; margin-bottom: 8px;">
                        <textarea name="itinerary[0][activities]" rows="2" placeholder="Activities for this day..." style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; resize: vertical;"></textarea>
                        <button type="button" onclick="removeItineraryItem(this)" style="margin-top: 8px; padding: 4px 8px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px;">Remove</button>
                    </div>
                @endif
            </div>

            <button type="button" onclick="addItineraryItem()" style="width: 100%; padding: 8px; background: #28a745; color: white; border: none; border-radius: 4px; margin-bottom: 16px;">
                + Add Day
            </button>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button type="button" onclick="hideItineraryForm()" style="padding: 12px; background: #6c757d; color: white; border: none; border-radius: 6px;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 500;">
                    Save Itinerary
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Packing List Modal -->
<div id="packingModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 20px; overflow-y: auto;">
    <div style="background: white; border-radius: 12px; max-width: 400px; margin: 20px auto; padding: 20px;">
        <h3 style="margin: 0 0 16px 0;">Packing List 🧳</h3>

        <form method="PUT" action="{{ route('trips.packing', $trip) }}">
            @csrf
            @method('PUT')

            <div id="packingItems">
                @if($trip->packing_list && count($trip->packing_list) > 0)
                    @foreach($trip->packing_list as $index => $item)
                        <div class="packing-item" style="display: flex; gap: 8px; margin-bottom: 8px;">
                            <input type="text" name="packing_list[]" value="{{ $item }}" placeholder="Item to pack..." style="flex: 1; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px;">
                            <button type="button" onclick="removePackingItem(this)" style="padding: 8px; background: #dc3545; color: white; border: none; border-radius: 4px;">×</button>
                        </div>
                    @endforeach
                @else
                    <div class="packing-item" style="display: flex; gap: 8px; margin-bottom: 8px;">
                        <input type="text" name="packing_list[]" placeholder="Item to pack..." style="flex: 1; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px;">
                        <button type="button" onclick="removePackingItem(this)" style="padding: 8px; background: #dc3545; color: white; border: none; border-radius: 4px;">×</button>
                    </div>
                @endif
            </div>

            <button type="button" onclick="addPackingItem()" style="width: 100%; padding: 8px; background: #28a745; color: white; border: none; border-radius: 4px; margin-bottom: 16px;">
                + Add Item
            </button>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button type="button" onclick="hidePackingForm()" style="padding: 12px; background: #6c757d; color: white; border: none; border-radius: 6px;">
                    Cancel
                </button>
                <button type="submit" style="padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 500;">
                    Save List
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let itineraryIndex = {{ $trip->itinerary ? count($trip->itinerary) : 1 }};

    function showItineraryForm() {
        document.getElementById('itineraryModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideItineraryForm() {
        document.getElementById('itineraryModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function addItineraryItem() {
        const container = document.getElementById('itineraryItems');
        const div = document.createElement('div');
        div.className = 'itinerary-item';
        div.style.cssText = 'margin-bottom: 16px; padding: 12px; border: 1px solid #e9ecef; border-radius: 8px;';
        div.innerHTML = `
            <input type="text" name="itinerary[${itineraryIndex}][day]" placeholder="Day ${itineraryIndex + 1}" style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; margin-bottom: 8px;">
            <textarea name="itinerary[${itineraryIndex}][activities]" rows="2" placeholder="Activities for this day..." style="width: 100%; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px; resize: vertical;"></textarea>
            <button type="button" onclick="removeItineraryItem(this)" style="margin-top: 8px; padding: 4px 8px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px;">Remove</button>
        `;
        container.appendChild(div);
        itineraryIndex++;
    }

    function removeItineraryItem(button) {
        button.parentElement.remove();
    }

    function showPackingForm() {
        document.getElementById('packingModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hidePackingForm() {
        document.getElementById('packingModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function addPackingItem() {
        const container = document.getElementById('packingItems');
        const div = document.createElement('div');
        div.className = 'packing-item';
        div.style.cssText = 'display: flex; gap: 8px; margin-bottom: 8px;';
        div.innerHTML = `
            <input type="text" name="packing_list[]" placeholder="Item to pack..." style="flex: 1; padding: 8px; border: 1px solid #e9ecef; border-radius: 4px;">
            <button type="button" onclick="removePackingItem(this)" style="padding: 8px; background: #dc3545; color: white; border: none; border-radius: 4px;">×</button>
        `;
        container.appendChild(div);
    }

    function removePackingItem(button) {
        button.parentElement.remove();
    }

    // Close modals when clicking outside
    document.getElementById('itineraryModal').addEventListener('click', function(e) {
        if (e.target === this) hideItineraryForm();
    });

    document.getElementById('packingModal').addEventListener('click', function(e) {
        if (e.target === this) hidePackingForm();
    });
</script>
@endsection
