<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class CovidService
{
    /**
     * @var string
     */
    protected $country;

    /**
     * @param string $country
     */
    public function __construct(string $country)
    {
        $this->country = $country;
    }

    /**
     * Get all daily stats for the country
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        $days = \Cache::remember('covid:country:'.$this->country, Carbon::parse('1 hour'), function () {
            $response = \Http::get(config('app.covid_api') . '/total/country/' . $this->country);

            if (! $response->ok()) {
                return collect([]);
            }

            return collect($response->json())->values();
        });

        return $days->map(function ($day, $index) use ($days) {
            return [
                'date' => Carbon::parse($day['Date'])->toDateString(),
                'weekday' => strtolower(Carbon::parse($day['Date'])->format('l')),
                'confirmed' => $index > 0 ? $day['Confirmed'] - $days[$index-1]['Confirmed'] : 0,
                'deaths' => $index > 0 ? $day['Deaths'] - $days[$index-1]['Deaths'] : 0,
                'recovered' => $index > 0 ? $day['Recovered'] - $days[$index-1]['Recovered'] : 0,
                'active' => $day['Active'],
                'raw' => [
                    'confirmed' => $day['Confirmed'],
                    'deaths' => $day['Deaths'],
                    'recovered' => $day['Recovered'],
                    'active' => $day['Active'],
                ],
            ];
        });
    }

    /**
     * Get last 14 days stats for country
     *
     * @return Collection
     */
    public function getLast14Days(): Collection
    {
        $days = $this->getAll();

        return $days->reverse()->slice(0, 14)->reverse()->values();
    }

    /**
     * Get country list
     *
     * @return Collection
     */
    public static function getCountries(): Collection
    {
        return \Cache::remember('covid19:countries', Carbon::parse('12 hours'), function () {
            $response = \Http::get(config('app.covid_api') . '/countries');

            return collect($response->json())->sortBy('Country')->values();
        });
    }

    /**
     * Get country info
     *
     * @return Collection
     */
    public function getCountryStats(): Collection
    {
        return \Cache::remember('countries:population:'.$this->country, Carbon::parse('30 days'), function () {
            $response = \Http::get('https://restcountries.eu/rest/v2/alpha/' . $this->country);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json());
        });
    }

    public function getNewCasesTrend()
    {
        $days = $this->getAll();
        $country = $this->getCountryStats();

        $days = $days->reverse()->values();

        return $days->map(function ($day, $index) use ($days) {
            $group = $days->slice($index, 14);

            if ($group->count() < 14) {
                return [];
            }

            return $this->getAggregatedStats($group);
        })
        ->filter()
        ->map(function ($day) use ($country) {
            return $day + [
                'new_cases_rate' => round($day['confirmed'] / ($country['population'] / 100000), 2),
            ];
        });
    }

    public function getAggregatedStats($days)
    {
        if ($days->count() != 14) {
            throw new \Exception('The aggregate function needs 14 days exactly. ' . $days->count() . ' given.');
        }

        $days = $days->reverse()->values();

        $data['date'] = $days->last()['date'];
        $data['weekday'] = $days->last()['weekday'];
        $data['confirmed'] = $days->sum('confirmed');
        $data['deaths'] = $days->sum('deaths');
        $data['recovered'] = $days->sum('recovered');

        return $data;
    }

    /**
     * Method to prepare view data
     *
     * @return array
     */
    public function prepareData(): array
    {
        $days = $this->getLast14Days();
        $countryStats = $this->getCountryStats();

        $data['country'] = $countryStats['name'];
        $data['population'] = $countryStats['population'] ?? 0;
        $data['flag'] = $countryStats['flag'] ?? null;

        if ($days->isEmpty()) {
            return $data;
        }

        $data['last_14_days']['confirmed'] = $days->sum('confirmed');
        $data['last_14_days']['deaths'] = $days->sum('deaths');
        $data['last_14_days']['recovered'] = $days->sum('recovered');
        $data['last_reported_day'] = $days->last()['date'];
        $data['new_cases_rate'] = round($data['last_14_days']['confirmed'] / ($data['population'] / 100000), 2);
        $data['days'] = $days;
        $data['trend'] = $this->getNewCasesTrend()->take(30)->reverse()->values();

        return $data;
    }
}
