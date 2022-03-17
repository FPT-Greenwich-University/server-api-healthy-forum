<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class PublicLocationController extends Controller
{
    protected string $domainApi = "";

    public function __construct()
    {
        $this->domainApi = "https://thongtindoanhnghiep.co/api/";
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
     * @param $cityID
     * @return mixed
     */
    public function getDistricts($cityID): mixed
    {
        try {
            $response = Http::get("$this->domainApi/city/$cityID/district");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Get all the wards of the distinct
     *
     * @param $distinctID
     * @return mixed
     */
    public function getWards($distinctID): mixed
    {
        try {
            $response = Http::get("$this->domainApi/district/$distinctID/ward");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
