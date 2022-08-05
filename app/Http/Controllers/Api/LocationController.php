<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    protected string $domainApi = '';

    public function __construct()
    {
        $this->domainApi = env("LOCATION_API_URL");  // Set base domain url
    }

    /**
     * Get all the cities
     *
     * @return mixed
     */
    public function getCities(): mixed
    {
        try {
            $response = Http::get("$this->domainApi/city");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Get all the districts of the city
     *
     * @param int $cityId
     * @return mixed
     */
    public function getDistricts(int $cityId): mixed
    {
        try {
            $response = Http::get("$this->domainApi/city/$cityId/district");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Get all the wards of the distinct
     *
     * @param int $distinctId
     * @return mixed
     */
    public function getWards(int $distinctId): mixed
    {
        try {
            $response = Http::get("$this->domainApi/district/$distinctId/ward");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
