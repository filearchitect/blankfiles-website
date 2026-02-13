<?php

$keys = array_values(array_filter(array_map(
    static fn ($value) => trim($value),
    explode(',', (string) env('API_KEYS', ''))
)));

return [
    'public_rate_limit' => (int) env('API_PUBLIC_RATE_LIMIT', 30),
    'api_key_rate_limit' => (int) env('API_KEY_RATE_LIMIT', 300),
    'keys' => $keys,
    'usage_log_channel' => env('API_USAGE_LOG_CHANNEL', 'api_usage'),
];
