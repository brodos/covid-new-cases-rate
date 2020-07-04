<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class NewCasesRateByCountry extends Component
{
    public $country;

    public $countries;

    public $romania;

    protected $updatesQueryString = [
        'country' => ['except' => ''],
    ];

    public function mount($romania)
    {
        $this->fill(request()->only('country'));
        $this->romania = $romania;
    }

    public function fetchCountryData()
    {
        if (! $this->country) {
            return [];
        }

        $country = strtoupper($this->country);

        $covidStats = $this->fetchCovidStats($country);
        $countryStats = $this->fetchCountryStats($country);
        $days = collect($covidStats)->reverse()->take(15)->values();

        if ($days->isEmpty()) {
            return [];
        }

        $prevDay = $days->pop();

        $data['country'] = $days->first()['Country'];
        $data['population'] = $countryStats['population'] ?? 0;
        $data['last_14_days']['confirmed'] = $days->first()['Confirmed'] - $days->last()['Confirmed'];
        $data['last_14_days']['deaths'] = $days->first()['Deaths'] - $days->last()['Deaths'];
        $data['last_14_days']['recovered'] = $days->first()['Recovered'] - $days->last()['Recovered'];
        $data['last_reported_day'] = Carbon::parse($days->first()['Date'])->toDateString();
        $data['new_cases_rate'] = round($data['last_14_days']['confirmed'] / ($data['population'] / 100000), 2);

        return $data;
    }

    protected function fetchCovidStats($country)
    {
        return \Cache::remember('covid:country:'.$country, Carbon::parse('10 minutes'), function () use ($country) {
            $response = \Http::get(config('app.covid_api') . '/total/country/' . $country);

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The :country is not valid',
                ]);
            }

            return $response->json();
        });
    }

    protected function fetchCountryStats($country)
    {
        return \Cache::remember('countries:population:'.$country, Carbon::parse('30 days'), function () use ($country) {
            $response = \Http::get('https://restcountries.eu/rest/v2/alpha/' . $country);

            if (! $response->ok()) {
                throw ValidationException::withMessages([
                    'country' => 'The '. $country .' is not valid',
                ]);
            }

            return $response->json();
        });
    }

    protected function fetchCountries()
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
