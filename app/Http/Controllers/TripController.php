<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\Trip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TripController extends Controller
{
    // Show trips
    public function index()
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        $upcomingTrips = Trip::where('couple_id', $couple->id)
            ->where('start_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->get();

        $pastTrips = Trip::where('couple_id', $couple->id)
            ->where('status', 'completed')
            ->orderBy('start_date', 'desc')
            ->limit(5)
            ->get();

        return view('trips.index', compact('upcomingTrips', 'pastTrips', 'couple'));
    }

    // Show create form
    public function create()
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        return view('trips.create', compact('couple'));
    }

    // Store new trip
    public function store(Request $request)
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'accommodation' => 'nullable|string|max:255',
            'transportation' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $validated['couple_id'] = $couple->id;
        $validated['status'] = 'planning';
        $validated['spent_amount'] = 0;

        Trip::create($validated);

        return redirect()->route('trips.index')->with('success', 'Trip planned successfully! ✈️');
    }

    // Show trip details
    public function show(Trip $trip)
    {
        return view('trips.show', compact('trip'));
    }

    // Update trip status
    public function updateStatus(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'status' => 'required|in:planning,booked,in_progress,completed,cancelled'
        ]);

        $trip->update($validated);

        $message = match($validated['status']) {
            'booked' => 'Trip booked! Get ready for adventure! 🎒',
            'in_progress' => 'Have an amazing trip! 🌟',
            'completed' => 'Welcome back! Hope it was incredible! 📸',
            'cancelled' => 'Trip cancelled. Maybe another destination next time! 😔',
            default => 'Trip status updated!'
        };

        return back()->with('success', $message);
    }

    // Add expense to trip
    public function addExpense(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        $trip->update([
            'spent_amount' => $trip->spent_amount + $validated['amount']
        ]);

        return back()->with('success', 'Expense added to trip budget!');
    }

    // Update packing list
    public function updatePackingList(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'packing_list' => 'nullable|array',
            'packing_list.*' => 'string|max:255'
        ]);

        $trip->update(['packing_list' => $validated['packing_list'] ?? []]);

        return back()->with('success', 'Packing list updated! 🧳');
    }

    // Update itinerary
    public function updateItinerary(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'itinerary' => 'nullable|array',
            'itinerary.*.day' => 'required|string',
            'itinerary.*.activities' => 'required|string'
        ]);

        $trip->update(['itinerary' => $validated['itinerary'] ?? []]);

        return back()->with('success', 'Itinerary updated! 📅');
    }
}
