<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackApiUsage
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $durationMs = (int) round((microtime(true) - $start) * 1000);

        $apiKey = $request->header('X-API-Key');
        $isApiKey = is_string($apiKey) && $apiKey !== '';
        $apiKeyHash = $isApiKey ? hash('sha256', $apiKey) : null;

        $payload = [
            'path' => $request->path(),
            'method' => $request->method(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $durationMs,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_api_key' => $isApiKey,
            'api_key_hash' => $apiKeyHash,
            'etag_present' => $response->headers->has('ETag'),
            'cache_not_modified' => $response->getStatusCode() === 304,
        ];

        $channel = (string) config('api.usage_log_channel', 'api_usage');

        try {
            Log::channel($channel)->info('api_usage', $payload);
        } catch (\Throwable $exception) {
            Log::info('api_usage_fallback', $payload);
        }

        return $response;
    }
}
