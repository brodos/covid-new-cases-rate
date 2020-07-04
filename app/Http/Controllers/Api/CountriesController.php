<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Support\CovidService;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    public function index()
    {
        return response()->json(
            CovidService::getCountries()
        );
    }
}
