<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class NewCasesRateByCountry extends Component
{
    public $country;

    public $countries;

    public function mount()
    {

    }

    public function fetchCountryData()
    {
        if (! $this->country) {
            return [];
        }

        $country = strtoupper($this->country);

        $covidStats = \Cache::remember('covid:country:'.$country, Carbon::parse('10 minutes'), function () use ($country) {
            $response = \Http::get(config('app.covid_api') . '/total/country/' . $country);

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The :country is not valid',
                ]);
            }

            return $response->json();
        });

        $countryStats = \Cache::remember('countries:population:'.$country, Carbon::parse('30 days'), function () use ($country) {
            $response = \Http::get('https://restcountries.eu/rest/v2/alpha/' . $country);

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The '. $country .' is not valid',
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

        return $data;
    }

    public function fetchCountries()
    {
        $this->countries = \Cache::remember('covid19:countries', \Carbon\Carbon::parse('5 minutes'), function () {
            $response = \Http::get(config('app.covid_api') . '/countries');
            return $response->json();
        });

        $this->countries = collect($this->countries)->sortBy('Country')->values()->toArray();
    }

    public function render()
    {
        if (! $this->countries) {
            $this->fetchCountries();
        }

        $data = $this->fetchCountryData();

        return view('livewire.new-cases-rate-by-country', compact('data'));
    }
}
