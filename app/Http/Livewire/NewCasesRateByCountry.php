<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Support\CovidService;

class NewCasesRateByCountry extends Component
{
    public $country;

    public $countries;

    public $romania;

    public $data;

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
        $covidService = (new CovidService($country));
        $countryStats = $covidService->getCountryStats();
        $days = $covidService->getLast14Days();

        if ($days->isEmpty()) {
            return [];
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
        $data['trend'] = $covidService->getNewCasesTrend()->take(30)->reverse()->values();

        $this->emit('dataFetched', $data['trend']->pluck('new_cases_rate'));

        return $data;
    }

    protected function fetchCountries()
    {
        $this->countries = CovidService::getCountries()->toArray();
    }

    public function render()
    {
        if (! $this->countries) {
            $this->fetchCountries();
        }

        $this->data = $this->fetchCountryData();

        return view('livewire.new-cases-rate-by-country');
    }
}
