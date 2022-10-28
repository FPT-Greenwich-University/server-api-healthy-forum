<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Psy\Util\Json;

class LocationController extends Controller
{
    private string $domainApi = '';

    public function __construct()
    {
        $this->domainApi = env("LOCATION_API_URL");  // Initial domain url
    }

    /**
     * Get all the cities
     */
    final public function getCities()
    {
        return Http::get("$this->domainApi/city")->json(); // Return all cities
    }

    /**
     * Get all the districts of the city
     *
     * @param int $cityId
     * @return array|mixed
     */
    final public function getDistricts(int $cityId)
    {
        return Http::get("$this->domainApi/city/$cityId/district")->json(); // Return all districts by city
    }

    /**
     * Get all the wards of the distinct
     *
     * @param int $distinctId
     * @return array|mixed
     */
    final public function getWards(int $distinctId)
    {
        return Http::get("$this->domainApi/district/$distinctId/ward")->json(); // Return all the wards by district
    }
}
