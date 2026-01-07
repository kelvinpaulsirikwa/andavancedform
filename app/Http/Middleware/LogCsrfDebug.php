<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogCsrfDebug
{
    public function handle($request, Closure $next)
    {
        $hasSession = $request->hasSession();
        $sessionId = null;
        $sessionToken = null;

        if ($hasSession) {
            try {
                $session = $request->session();
                $sessionId = $session ? $session->getId() : null;
                $sessionToken = $session ? $session->token() : null;
            } catch (\Throwable $e) {
                $hasSession = false;
            }
        }

        Log::info('csrf debug pre-verify', [
            'path' => $request->path(),
            'method' => $request->method(),
            'session_id' => $sessionId,
            'session_token' => $sessionToken,
            'request_token' => $request->_token,
            'has_session' => $hasSession,
            'has_session_cookie' => $request->hasCookie(config('session.cookie')),
            'xsrf_cookie' => $request->cookie('XSRF-TOKEN'),
        ]);

        return $next($request);
    }
}

