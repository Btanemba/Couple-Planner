<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\DatePlan;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        // Check if we're in mobile environment
        $isMobile = config('app.env') === 'production' && config('nativephp.server_url');

        if ($isMobile) {
            // For mobile, get user from session
            $userSession = session('user');
            if (!$userSession) {
                return redirect()->route('login');
            }

            // Create a mock user object for mobile
            $user = (object) $userSession;
            $user->isInCouple = function() { return false; }; // Simplified for mobile
        } else {
            $user = Auth::user();
        }

        // Check if user is in a couple
        if (!$user->isInCouple()) {
            return redirect()->route('couple.setup')
                ->with('info', 'Create or join a couple to start planning together! 💕');
        }

        $couple = $user->couple;

        // If couple exists but isn't complete, show waiting dashboard
        if ($couple && !$couple->isComplete()) {
            return view('dashboard', [
                'couple' => $couple,
                'waitingForPartner' => true,
                'upcomingDates' => collect([]), // Empty collection
                'upcomingTrips' => collect([]), // Empty collection
                'relationshipStats' => [
                    'days_together' => $couple->relationship_duration,
                    'dates_planned' => 0,
                    'trips_planned' => 0
                ]
            ]);
        }

        // If no couple at all, redirect to setup
        if (!$couple) {
            return redirect()->route('couple.setup')
                ->with('info', 'Create or join a couple to start planning together! 💕');
        }

        // Get dashboard data
        $upcomingDates = DatePlan::where('couple_id', $couple->id)
            ->where('planned_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('planned_date')
            ->limit(3)
            ->get();

        $upcomingTrips = Trip::where('couple_id', $couple->id)
            ->where('start_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->limit(2)
            ->get();

        $recentDates = DatePlan::where('couple_id', $couple->id)
            ->where('status', 'completed')
            ->orderBy('planned_date', 'desc')
            ->limit(3)
            ->get();

        // Calculate some stats
        $totalDatesPlanned = DatePlan::where('couple_id', $couple->id)->count();
        $totalTripsPlanned = Trip::where('couple_id', $couple->id)->count();
        $completedDates = DatePlan::where('couple_id', $couple->id)->where('status', 'completed')->count();

        return view('dashboard', compact(
            'couple',
            'upcomingDates',
            'upcomingTrips',
            'recentDates',
            'totalDatesPlanned',
            'totalTripsPlanned',
            'completedDates'
        ));
    }

    public function quickAdd(Request $request)
    {
        $type = $request->input('type'); // 'date' or 'trip'

        if ($type === 'date') {
            return redirect()->route('dates.create');
        } elseif ($type === 'trip') {
            return redirect()->route('trips.create');
        }

        return back();
    }
}
