<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class MobileAuthController extends Controller
{
    private function getApiUrl(): string
    {
        return config('services.couple_api.base_url', 'https://couple-planner.vercel.app/api');
    }

    /**
     * Handle mobile registration
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        try {
            $response = Http::timeout(10)->post($this->getApiUrl() . '/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Store user info in session for mobile app
                Session::put('user', [
                    'id' => $data['user']['id'] ?? null,
                    'name' => $data['user']['name'] ?? $request->name,
                    'email' => $data['user']['email'] ?? $request->email,
                ]);

                Session::put('authenticated', true);

                return redirect()->route('dashboard')->with('success', 'Registration successful!');
            } else {
                $error = $response->json()['message'] ?? 'Registration failed';
                return back()->withErrors(['email' => $error])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Unable to connect to authentication server. Please try again.'])->withInput();
        }
    }

    /**
     * Handle mobile login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            $response = Http::timeout(10)->post($this->getApiUrl() . '/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Store user info in session for mobile app
                Session::put('user', [
                    'id' => $data['user']['id'] ?? null,
                    'name' => $data['user']['name'] ?? 'User',
                    'email' => $data['user']['email'] ?? $request->email,
                ]);

                Session::put('authenticated', true);

                return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
            } else {
                $error = $response->json()['message'] ?? 'Invalid credentials';
                return back()->withErrors(['email' => $error])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Unable to connect to authentication server. Please try again.'])->withInput();
        }
    }

    /**
     * Handle mobile logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Session::forget(['user', 'authenticated']);
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
