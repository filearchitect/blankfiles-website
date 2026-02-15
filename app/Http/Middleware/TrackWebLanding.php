<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackWebLanding
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $durationMs = (int) round((microtime(true) - $start) * 1000);

        $payload = [
            'path' => $request->path(),
            'method' => $request->method(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $durationMs,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->headers->get('referer'),
        ];

        try {
            Log::channel('web_traffic')->info('web_landing', $payload);
        } catch (\Throwable $exception) {
            Log::info('web_landing_fallback', $payload);
        }

        return $response;
    }
}
