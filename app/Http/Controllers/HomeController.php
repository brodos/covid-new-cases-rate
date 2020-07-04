<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Support\CovidService;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function index()
    {
        $roCovidService = new CovidService('RO');
        $roData = $roCovidService->prepareData();
        $countryData = [];

        $countries = CovidService::getCountries();

        if ($selectedCountry = request()->get('country', null)) {
            $countryCovidService = new CovidService(strtoupper($selectedCountry));
            $countryData = $countryCovidService->prepareData();
        }

        return view('home', compact('roData', 'countryData', 'countries', 'selectedCountry'));
    }
}
