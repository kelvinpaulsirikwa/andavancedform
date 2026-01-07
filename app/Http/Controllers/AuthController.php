<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard.questions');
        }
        
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.questions'))
                ->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Log::info('logout CSRF check', [
            'session_id' => session()->getId(),
            'session_token' => session()->token(),
            'request_token' => $request->_token,
            'has_session_cookie' => $request->hasCookie(config('session.cookie')),
        ]);

        Log::info('Logout requested', [
            'user_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
            'host' => $request->getHost(),
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'csrf_token_present' => $request->has('_token'),
        ]);

        Log::info('Logout step: calling Auth::logout', [
            'user_id' => Auth::id(),
        ]);
        Auth::logout();

        Log::info('Logout step: session invalidate', [
            'old_session_id' => $request->session()->getId(),
        ]);
        $request->session()->invalidate();

        Log::info('Logout step: regenerate CSRF token');
        $request->session()->regenerateToken();
        
        // Clear all cache
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            Log::info('Logout step: cache cleared');
        } catch (\Exception $e) {
            Log::warning('Logout cache clear failed', ['message' => $e->getMessage()]);
        }

        Log::info('Logout step: redirecting to home');
        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}


