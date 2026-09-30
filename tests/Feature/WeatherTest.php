<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    use RefreshDatabase;

    public function test_weather_endpoint_returns_weather_data(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Cache::forget('weather.perth');

        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'latitude' => -31.95079,
                'longitude' => 115.807236,
                'generationtime_ms' => 0.07808208465576172,
                'utc_offset_seconds' => 28800,
                'timezone' => 'Australia/Perth',
                'timezone_abbreviation' => 'GMT+8',
                'elevation' => 23,
                'current_units' => [
                    'time' => 'iso8601',
                    'interval' => 'seconds',
                    'weather_code' => 'wmo code',
                ],
                'current' => [
                    'time' => '2026-09-30T13:15',
                    'interval' => 900,
                    'weather_code' => 0,
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/weather');

        $response
            ->assertStatus(200)
            ->assertJsonPath('timezone', 'Australia/Perth')
            ->assertJsonPath('current_units.weather_code', 'wmo code')
            ->assertJsonPath('current.weather_code', 0);

        Http::assertSent(function ($request) {
            return str_starts_with(
                $request->url(),
                'https://api.open-meteo.com/v1/forecast'
            )
                && $request['latitude'] == -31.9523
                && $request['longitude'] == 115.8613
                && $request['current'] === 'weather_code'
                && $request['timezone'] === 'Australia/Perth';
        });
    }
}
