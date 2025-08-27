<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    public function getProvinces()
    {
        return Cache::remember('provinces', 3600, function () {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            return $response->json();
        });
    }

    public function getRegencies($provinceId)
    {
        return Cache::remember("regencies_{$provinceId}", 3600, function () use ($provinceId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
            return $response->json();
        });
    }

    public function getDistricts($regencyId)
    {
        return Cache::remember("districts_{$regencyId}", 3600, function () use ($regencyId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regencyId}.json");
            return $response->json();
        });
    }

    public function getVillages($districtId)
    {
        return Cache::remember("villages_{$districtId}", 3600, function () use ($districtId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            return $response->json();
        });
    }
}