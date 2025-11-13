<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoupleSetupController extends Controller
{
    // Show setup form (first time app usage)
    public function index()
    {
        $user = Auth::user();

        // If user is already in a couple, redirect to dashboard
        if ($user->isInCouple()) {
            return redirect()->route('dashboard');
        }

        return view('couple.setup');
    }

    // Create new couple (first partner)
    public function create(Request $request)
    {
        $user = Auth::user();

        // If user is already in a couple, redirect to dashboard
        if ($user->isInCouple()) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'partner_one_name' => 'required|string|max:255',
            'partner_two_name' => 'required|string|max:255',
            'relationship_start_date' => 'required|date|before:today',
            'relationship_description' => 'nullable|string|max:500',
            'anniversary_reminder' => 'required|in:weekly,monthly,yearly'
        ]);

        // Create the couple
        $couple = Couple::create(array_merge($validated, [
            'partner_one_id' => $user->id,
            'status' => 'waiting_for_partner',
            'invitation_code' => $this->generateInvitationCode(),
        ]));

        // Update user's couple_id
        $user->update(['couple_id' => $couple->id]);

        return redirect()->route('couple.invitation-code')
            ->with('success', 'Couple created! Share your invitation code with ' . $validated['partner_two_name']);
    }

    // Generate unique invitation code
    private function generateInvitationCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (Couple::where('invitation_code', $code)->exists());

        return $code;
    }

    // Show couple profile/settings
    public function profile()
    {
        $user = Auth::user();

        if (!$user->isInCouple()) {
            return redirect()->route('couple.setup');
        }

        $couple = $user->couple;

        return view('couple.profile', compact('couple'));
    }

    // Update couple information
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isInCouple()) {
            return redirect()->route('couple.setup');
        }

        $couple = $user->couple;

        $validated = $request->validate([
            'partner_one_name' => 'required|string|max:255',
            'partner_two_name' => 'required|string|max:255',
            'relationship_start_date' => 'required|date|before:today',
            'relationship_description' => 'nullable|string|max:500',
            'anniversary_reminder' => 'required|in:weekly,monthly,yearly'
        ]);

        $couple->update($validated);

        return back()->with('success', 'Profile updated successfully! 😊');
    }

    // Add milestone
    public function addMilestone(Request $request)
    {
        $user = Auth::user();

        if (!$user->isInCouple()) {
            return redirect()->route('couple.setup');
        }

        $couple = $user->couple;

        $validated = $request->validate([
            'milestone_title' => 'required|string|max:255',
            'milestone_date' => 'required|date',
            'milestone_description' => 'nullable|string|max:255'
        ]);

        $milestones = $couple->milestones ?? [];
        $milestones[] = [
            'title' => $validated['milestone_title'],
            'date' => $validated['milestone_date'],
            'description' => $validated['milestone_description'] ?? '',
            'added_at' => now()->toDateString()
        ];

        $couple->update(['milestones' => $milestones]);

        return back()->with('success', 'Milestone added! 🎉');
    }
}
