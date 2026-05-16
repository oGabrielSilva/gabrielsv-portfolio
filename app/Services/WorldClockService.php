<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorldClockService
{
    private const CACHE_TTL_DAYS = 30;
    private const SEARCH_URL = 'https://nominatim.openstreetmap.org/search';

    /**
     * Search for cities by name. Returns a slim array suitable for the
     * frontend autocomplete: name, country, lat, lon, country_code.
     *
     * Nominatim's free tier asks for max 1 req/s and an identifying
     * User-Agent — both honored here. Results are cached for 30 days
     * per normalized query so repeat lookups don't hit the API at all.
     *
     * @return array<int, array{name:string, country:string, lat:float, lon:float, country_code:string|null}>
     */
    public function search(string $query): array
    {
        $normalized = mb_strtolower(trim($query));
        if ($normalized === '' || mb_strlen($normalized) < 2) {
            return [];
        }

        $cacheKey = 'wc:search:' . md5($normalized);

        return Cache::remember($cacheKey, now()->addDays(self::CACHE_TTL_DAYS), function () use ($normalized) {
            try {
                $response = Http::withHeaders([
                        'User-Agent' => config('app.contact_ua'),
                        'Accept' => 'application/json',
                        'Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8',
                    ])
                    ->timeout(5)
                    ->get(self::SEARCH_URL, [
                        'q' => $normalized,
                        'format' => 'jsonv2',
                        'addressdetails' => 1,
                        'limit' => 8,
                        'accept-language' => 'pt-BR',
                    ]);

                if (!$response->successful()) {
                    Log::warning('Nominatim returned non-200', ['status' => $response->status()]);
                    return [];
                }

                return $this->shape($response->json() ?? []);
            } catch (\Throwable $e) {
                Log::warning('Nominatim search failed: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Reduce Nominatim's verbose payload to the fields the UI needs and
     * drop entries that aren't populated places.
     */
    private function shape(array $items): array
    {
        $allowedTypes = ['city', 'town', 'village', 'hamlet', 'administrative', 'municipality', 'state', 'county'];

        $out = [];
        $seen = [];

        foreach ($items as $item) {
            $type = $item['type'] ?? null;
            $class = $item['class'] ?? null;
            if ($class !== 'boundary' && $class !== 'place') continue;
            if ($type && !in_array($type, $allowedTypes, true)) continue;

            $address = $item['address'] ?? [];
            $name = $address['city']
                ?? $address['town']
                ?? $address['village']
                ?? $address['hamlet']
                ?? $address['municipality']
                ?? $address['state']
                ?? $item['name']
                ?? null;
            if (!$name) continue;

            $country = $address['country'] ?? '';
            $countryCode = isset($address['country_code']) ? strtoupper($address['country_code']) : null;
            $lat = isset($item['lat']) ? (float) $item['lat'] : null;
            $lon = isset($item['lon']) ? (float) $item['lon'] : null;
            if ($lat === null || $lon === null) continue;

            $dedupKey = strtolower("$name|$country");
            if (isset($seen[$dedupKey])) continue;
            $seen[$dedupKey] = true;

            $out[] = [
                'name' => $name,
                'country' => $country,
                'country_code' => $countryCode,
                'lat' => $lat,
                'lon' => $lon,
            ];
        }

        return $out;
    }
}
