<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Support\CovidService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class NewCasesRateController extends Controller
{
    public function show($country)
    {
        $country = strtoupper($country);
        $covidService = (new CovidService($country));
        $countryStats = $covidService->getCountryStats();
        $days = $covidService->getLast14Days();

        if ($days->isEmpty()) {
            return response()->json([]);
        }

        $data['country'] = $countryStats['name'];
        $data['population'] = $countryStats['population'] ?? 0;
        $data['flag'] = $countryStats['flag'] ?? null;
        $data['last_14_days']['confirmed'] = $days->sum('confirmed');
        $data['last_14_days']['deaths'] = $days->sum('deaths');
        $data['last_14_days']['recovered'] = $days->sum('recovered');
        $data['last_reported_day'] = $days->last()['date'];
        $data['new_cases_rate'] = round($data['last_14_days']['confirmed'] / ($data['population'] / 100000), 2);
        $data['days'] = $days;

        return response()->json($data);
    }
}
