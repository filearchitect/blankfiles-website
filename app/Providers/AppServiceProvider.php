<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('files-api', function (Request $request) {
            $publicLimit = (int) config('api.public_rate_limit', 30);
            $apiKeyLimit = (int) config('api.api_key_rate_limit', 300);
            $validKeys = collect(config('api.keys', []))->filter();

            $apiKey = $request->header('X-API-Key');
            if (!$apiKey) {
                $authorization = (string) $request->header('Authorization', '');
                if (Str::startsWith($authorization, 'Bearer ')) {
                    $apiKey = trim(Str::after($authorization, 'Bearer '));
                }
            }

            $isValidApiKey = is_string($apiKey) && $apiKey !== '' && $validKeys->contains($apiKey);
            $limit = $isValidApiKey ? $apiKeyLimit : $publicLimit;
            $identifier = $isValidApiKey
                ? 'api_key:' . hash('sha256', $apiKey)
                : 'ip:' . ($request->ip() ?? 'unknown');

            return Limit::perMinute($limit)->by($identifier)->response(function () use ($limit, $isValidApiKey) {
                return response()->json([
                    'error' => 'Rate limit exceeded.',
                    'meta' => [
                        'limit_per_minute' => $limit,
                        'api_key_valid' => $isValidApiKey,
                    ],
                ], 429);
            });
        });
    }
}
