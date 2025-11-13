<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoupleInvitationController extends Controller
{
    // Show invitation creation form
    public function create()
    {
        $user = Auth::user();

        // Check if user already has a couple
        if ($user->isInCouple()) {
            return redirect()->route('dashboard')->with('info', 'You are already in a couple relationship!');
        }

        return view('couple.invite');
    }

    // Create couple and generate invitation code
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isInCouple()) {
            return redirect()->route('dashboard')->with('info', 'You are already in a couple relationship!');
        }

        $validated = $request->validate([
            'partner_one_name' => 'required|string|max:255',
            'partner_two_name' => 'required|string|max:255',
            'relationship_start_date' => 'required|date|before:today',
            'relationship_description' => 'nullable|string|max:500',
            'anniversary_reminder' => 'required|in:weekly,monthly,yearly'
        ]);

        // Create couple with invitation code
        $couple = Couple::create([
            'partner_one_id' => $user->id,
            'partner_one_name' => $validated['partner_one_name'],
            'partner_two_name' => $validated['partner_two_name'],
            'relationship_start_date' => $validated['relationship_start_date'],
            'relationship_description' => $validated['relationship_description'],
            'anniversary_reminder' => $validated['anniversary_reminder'],
            'invitation_code' => Couple::generateInvitationCode(),
            'status' => 'pending'
        ]);

        // Update user
        $user->update([
            'couple_id' => $couple->id,
            'partner_role' => 'partner_one'
        ]);

        return redirect()->route('couple.invitation.show', $couple)
            ->with('success', 'Couple created! Share the invitation code with your partner.');
    }

    // Show invitation code
    public function show(Couple $couple)
    {
        $user = Auth::user();

        if ($couple->partner_one_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('couple.invitation-code', compact('couple'));
    }

    // Join couple with invitation code
    public function acceptInvitation(Request $request)
    {
        $user = Auth::user();

        if ($user->isInCouple()) {
            return redirect()->route('dashboard')->with('info', 'You are already in a couple relationship!');
        }

        $validated = $request->validate([
            'invitation_code' => 'required|string|size:6'
        ]);

        $couple = Couple::where('invitation_code', strtoupper($validated['invitation_code']))
            ->where('status', 'pending')
            ->first();

        if (!$couple) {
            return back()->withErrors(['invitation_code' => 'Invalid or expired invitation code.']);
        }

        if ($couple->partner_one_id === $user->id) {
            return back()->withErrors(['invitation_code' => 'You cannot join your own couple invitation.']);
        }

        // Update couple with second partner
        $couple->update([
            'partner_two_id' => $user->id,
            'status' => 'active',
            'invitation_code' => null // Remove code after use
        ]);

        // Update user
        $user->update([
            'couple_id' => $couple->id,
            'partner_role' => 'partner_two'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Successfully joined couple! Welcome to your shared planner! 💕');
    }

    // Leave couple (disconnect)
    public function leave(Request $request)
    {
        $user = Auth::user();

        if (!$user->isInCouple()) {
            return redirect()->route('dashboard');
        }

        $couple = $user->couple;

        // If user is partner_one and partner_two exists, transfer ownership
        if ($user->partner_role === 'partner_one' && $couple->partner_two_id) {
            $couple->update([
                'partner_one_id' => $couple->partner_two_id,
                'partner_two_id' => null,
                'status' => 'pending',
                'invitation_code' => Couple::generateInvitationCode()
            ]);

            $partner = User::find($couple->partner_one_id);
            $partner->update(['partner_role' => 'partner_one']);
        } else {
            // Remove partner_two or delete couple if no one left
            if ($user->partner_role === 'partner_two') {
                $couple->update([
                    'partner_two_id' => null,
                    'status' => 'pending',
                    'invitation_code' => Couple::generateInvitationCode()
                ]);
            } else {
                // Delete couple if creator leaves and no partner
                $couple->delete();
            }
        }

        // Update user
        $user->update([
            'couple_id' => null,
            'partner_role' => null
        ]);

        return redirect()->route('couple.invite')
            ->with('info', 'You have left the couple. You can create a new one or join another.');
    }

    // Show invitation code (for routes that expect showInvitationCode method)
    public function showInvitationCode()
    {
        $user = Auth::user();

        if (!$user->isInCouple()) {
            return redirect()->route('couple.setup');
        }

        $couple = $user->couple;

        if (!$couple || $couple->status !== 'waiting_for_partner') {
            return redirect()->route('dashboard');
        }

        return view('couple.invitation-code', compact('couple'));
    }

    // Show join form (for routes that expect showJoin method)
    public function showJoin()
    {
        $user = Auth::user();

        if ($user->isInCouple()) {
            return redirect()->route('dashboard')->with('info', 'You are already in a couple relationship!');
        }

        return view('couple.join');
    }

    // Handle join form (both GET and POST)
    public function join(Request $request = null)
    {
        $user = Auth::user();

        if ($user->isInCouple()) {
            return redirect()->route('dashboard')->with('info', 'You are already in a couple relationship!');
        }

        // If this is a GET request, show the form
        if (!$request || $request->isMethod('GET')) {
            return view('couple.join');
        }

        // Handle POST request (form submission)
        $validated = $request->validate([
            'invitation_code' => 'required|string|size:6'
        ]);

        $couple = Couple::where('invitation_code', strtoupper($validated['invitation_code']))
            ->where('status', 'waiting_for_partner')
            ->first();

        if (!$couple) {
            return back()->withErrors(['invitation_code' => 'Invalid or expired invitation code.']);
        }

        if ($couple->partner_one_id === $user->id) {
            return back()->withErrors(['invitation_code' => 'You cannot join your own couple invitation.']);
        }

        // Update couple with second partner
        $couple->update([
            'partner_two_id' => $user->id,
            'status' => 'active',
            'invitation_code' => null // Remove code after use
        ]);

        // Update user's couple_id
        $user->update(['couple_id' => $couple->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Successfully joined the couple! Welcome to your shared planner! 💕');
    }
}
