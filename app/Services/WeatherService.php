<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getCurrentWeather(): array
    {
        return Cache::remember(
            'weather.perth',
            now()->addMinutes(15),
            function () {
                $response = Http::withoutVerifying()->timeout(5)->get(
                    'https://api.open-meteo.com/v1/forecast',
                    [
                        'latitude' => -31.9523,
                        'longitude' => 115.8613,
                        'current' => 'weather_code',
                        'timezone' => 'Australia/Perth',
                    ]
                );

                $response->throw();

                return $response->json();
            }
        );
    }
}
