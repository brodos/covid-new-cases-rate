<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function index()
    {
        $covidStats = \Cache::remember('covid:country:romania', Carbon::parse('10 minutes'), function () {
            $response = \Http::get(config('app.covid_api') . '/total/country/romania');

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The :country is not valid',
                ]);
            }

            return $response->json();
        });

        $countryStats = \Cache::remember('countries:population:RO', Carbon::parse('30 days'), function () {
            $response = \Http::get('https://restcountries.eu/rest/v2/alpha/RO');

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The :country is not valid',
                ]);
            }

            return $response->json();
        });

        $days = collect($covidStats)->reverse()->take(15)->values();
        $prevDay = $days->pop();

        $data['country'] = $days->first()['Country'];
        $data['population'] = $countryStats['population'] ?? 0;
        $data['last_14_days']['confirmed'] = $days->first()['Confirmed'] - $days->last()['Confirmed'];
        $data['last_14_days']['deaths'] = $days->first()['Deaths'] - $days->last()['Deaths'];
        $data['last_14_days']['recovered'] = $days->first()['Recovered'] - $days->last()['Recovered'];
        $data['last_reported_day'] = Carbon::parse($days->first()['Date'])->toDateString();
        $data['new_cases_rate'] = round($data['last_14_days']['confirmed'] / ($data['population'] / 100000), 2);

        $days = $days->reverse()->values();

        $data['days'] = $days->map(function ($day, $index) use ($days, $prevDay) {
            return [
                'date' => Carbon::parse($day['Date'])->toDateString(),
                'weekday' => strtolower(Carbon::parse($day['Date'])->format('l')),
                'confirmed' => $index > 0 ? $day['Confirmed'] - $days[$index-1]['Confirmed'] : $day['Confirmed'] - $prevDay['Confirmed'],
                'deaths' => $day['Deaths'],
                'recovered' => $day['Recovered'],
                'active' => $day['Active'],
            ];
        });

        return view('home', compact('data'));
    }
}
