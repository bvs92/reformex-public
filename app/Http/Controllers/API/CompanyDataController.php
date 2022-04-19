<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class CompanyDataController extends Controller
{
    public function get_by_cui($cui)
    {
        $url = "https://api.openapi.ro/api/companies/" . $cui;

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'x-api-key' => config('services.openapi.api_key'),
            'Access-Control-Allow-Origin' => '*',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url);

        return response()->json(json_decode($response));
    }
}
