<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    protected string $domainApi = "";

    public function __construct()
    {
        $this->domainApi = "https://thongtindoanhnghiep.co/api/";
    }

    public function getCities()
    {
        try {
            $response = Http::get("$this->domainApi/city");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getDistricts($cityID)
    {
        try {
            $response = Http::get("$this->domainApi/city/$cityID/district");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getWards($distinctID)
    {
        try {
            $response = Http::get("$this->domainApi/district/$distinctID/ward");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
