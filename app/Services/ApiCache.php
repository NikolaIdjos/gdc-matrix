<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Service for caching API results.
 */
class ApiCache
{
    /**
     * Generate a unique cache key from two parts.
     *
     * @param  string  $firstPart
     * @param  string  $secondPart
     * @return string
     */
    public function generateCacheKey(string $firstPart, string $secondPart): string
    {
        return $firstPart . $secondPart;
    }

    /**
     * Retrieve data from cache or store it if missing.
     *
     * @param  string   $cacheKey
     * @param  int      $seconds  0 = cache forever
     * @param  \Closure $callback
     * @return array
     */
    public function remember(string $cacheKey, int $seconds, \Closure $callback): array
    {
        if ($seconds === 0) {
            return Cache::rememberForever($cacheKey, $callback);
        }

        return Cache::remember($cacheKey, $seconds, $callback);
    }
}
