<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getCities()
    {
        try {
            $response = Http::get("https://thongtindoanhnghiep.co/api/city");
            return $response->json();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function getDistricts($cityID)
    {
        try {
            $response = Http::get("https://thongtindoanhnghiep.co/api/city/$cityID/district");
            return $response->json();
        } catch (Exception $exception) {
            return reponse()->json($exception->getMessage());
        }
    }

    public function getWards($distinctID)
    {
        try {
            $response = Http::get("https://thongtindoanhnghiep.co/api/district/$distinctID/ward");
            return $response->json();
        } catch (Exception $exception) {
            return reponse()->json($exception->getMessage());
        }
    }
}
