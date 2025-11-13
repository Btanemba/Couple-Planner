<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\DatePlan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DateController extends Controller
{
    // Show upcoming dates
    public function index()
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        $upcomingDates = DatePlan::where('couple_id', $couple->id)
            ->where('planned_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('planned_date')
            ->get();

        $pastDates = DatePlan::where('couple_id', $couple->id)
            ->where('status', 'completed')
            ->orderBy('planned_date', 'desc')
            ->limit(10)
            ->get();

        return view('dates.index', compact('upcomingDates', 'pastDates', 'couple'));
    }

    // Show create form
    public function create()
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        return view('dates.create', compact('couple'));
    }

    // Store new date
    public function store(Request $request)
    {
        $couple = Couple::first();
        if (!$couple) {
            return redirect()->route('couple.setup');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_date' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'category' => 'required|in:romantic,adventure,casual,special,other',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $validated['couple_id'] = $couple->id;
        $validated['status'] = 'planned';

        DatePlan::create($validated);

        return redirect()->route('dates.index')->with('success', 'Date planned successfully! 💕');
    }

    // Show date details
    public function show(DatePlan $date)
    {
        return view('dates.show', compact('date'));
    }

    // Update date status (mark as completed, etc.)
    public function updateStatus(Request $request, DatePlan $date)
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,confirmed,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $date->update($validated);

        $message = match($validated['status']) {
            'completed' => 'Date marked as completed! Hope you had fun! 😊',
            'confirmed' => 'Date confirmed! 🎉',
            'cancelled' => 'Date cancelled. Maybe next time! 😔',
            default => 'Date updated!'
        };

        return back()->with('success', $message);
    }

    // Quick complete a date
    public function complete(DatePlan $date)
    {
        $date->update(['status' => 'completed']);
        return back()->with('success', 'Date completed! Hope it was amazing! 💕');
    }

    // Cancel a date
    public function cancel(DatePlan $date)
    {
        $date->update(['status' => 'cancelled']);
        return back()->with('info', 'Date cancelled. You can always plan another one! 😊');
    }
}
