<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Support\CovidService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class NewCasesRateTrendController extends Controller
{
    public function show($country)
    {
        $country = strtoupper($country);

        $covidService = (new CovidService($country));

        return response()->json(
            $covidService->getNewCasesTrend()->take(30)->reverse()->values()
        );
    }
}
