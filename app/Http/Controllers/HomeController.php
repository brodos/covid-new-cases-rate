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
        $covidService = (new CovidService('RO'));
        $countryStats = $covidService->getCountryStats();

        $days = $covidService->getLast14Days();

        $data['country'] = $countryStats['name'];
        $data['population'] = $countryStats['population'] ?? 0;
        $data['flag'] = $countryStats['flag'] ?? null;
        $data['last_14_days']['confirmed'] = $days->sum('confirmed');
        $data['last_14_days']['deaths'] = $days->sum('deaths');
        $data['last_14_days']['recovered'] = $days->sum('recovered');
        $data['last_reported_day'] = $days->last()['date'];
        $data['new_cases_rate'] = round($data['last_14_days']['confirmed'] / ($data['population'] / 100000), 2);
        $data['days'] = $days;
        $data['trend'] = $covidService->getNewCasesTrend()->take(30)->reverse()->values();

        // dd($data['trend']->pluck('new_cases_rate'));

        return view('home', compact('data'));
    }
}
