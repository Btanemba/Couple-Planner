<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MobileAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $isMobile = config('app.env') === 'production' && config('nativephp.server_url');

        if ($isMobile) {
            if (!Session::get('authenticated')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                return redirect()->route('login');
            }
        } else {
            if (!auth()->check()) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
