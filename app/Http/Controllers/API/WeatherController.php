<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class WeatherController extends Controller
{
    public function weather(WeatherService $weatherService): JsonResponse
    {
        try {
            $weather = $weatherService->getCurrentWeather();

            info($weather);

            return response()->json($weather);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Unable to retrieve weather data.',
            ], 502);
        }
    }
}
