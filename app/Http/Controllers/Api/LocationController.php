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
    final public function getCities(): mixed
    {
        try {
            return Http::get("$this->domainApi/city")->json();
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
    final public function getDistricts(int $cityId): mixed
    {
        try {
            return Http::get("$this->domainApi/city/$cityId/district")->json();
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
    final public function getWards(int $distinctId): mixed
    {
        try {
            return Http::get("$this->domainApi/district/$distinctId/ward")->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
