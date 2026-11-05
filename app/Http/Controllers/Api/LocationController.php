<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts(Request $request)
    {
        $province = $request->query('province');

        if (!$province) {
            return response()->json(['error' => 'Province parameter is required'], 400);
        }

        $districtsData = json_decode(file_get_contents(public_path('js/turkey-districts.json')), true);

        $districts = $districtsData[$province] ?? [];

        return response()->json([
            'province' => $province,
            'districts' => $districts
        ]);
    }

    public function getProvinces()
    {
        $districtsData = json_decode(file_get_contents(public_path('js/turkey-districts.json')), true);

        return response()->json([
            'provinces' => array_keys($districtsData)
        ]);
    }
}
