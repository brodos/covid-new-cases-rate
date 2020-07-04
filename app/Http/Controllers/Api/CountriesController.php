<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $json = \Cache::remember('covid19:countries', \Carbon\Carbon::parse('5 minutes'), function () {
            $response = \Http::get(config('app.covid_api') . '/countries');
            return $response->json();
        });

        return response()->json(
            $json
        );
    }
}
