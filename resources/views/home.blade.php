@extends('layouts.app')

@section('content')
    <div class="px-6 lg:px-0 max-w-md mx-auto w-full">
        <h1 class="text-center text-3xl md:text-4xl">
            COVID-19 new cases rate in the last 14 days per 100k inhabitants
        </h1>
    </div>
    <div class="hidden mt-4 px-6 lg:px-0 max-w-md mx-auto w-full">
        <div class="flex justify-between">
            <label for="country" class="text-gray-600 sr-only">Pick country to compare</label>
        </div>
        <script>
            let countrySelect = () => {
                return {
                    loading: false,
                    selectCountry(event) {
                        this.loading = true
                        window.location.href = '/?country=' + event.target.value
                    }
                }
            }
        </script>
        <div class="mt-1 flex items-center" x-data="countrySelect()">
            <div class="relative w-full">
                <select class="form-select py-3 w-full text-gray-800" name="country" id="country" @change="selectCountry">
                    <option value selected>- pick a country to compare -</option>
                    @foreach ($countries as $c)
                        <option
                            value="{{ $c['ISO2'] }}"
                            @if ($selectedCountry == $c['ISO2']) selected @endif
                        >{{ $c['Country'] }}</option>
                    @endforeach
                </select>

                <div x-cloak class="absolute inset-y-0 flex items-center right-0 mr-12">
                    <span class="spinner" x-show="loading"></span>
                </div>
            </div>
        </div>
    </div>

    <div class=" mt-4 px-6 lg:px-0 max-w-md mx-auto w-full">
        <div class="flex justify-between">
            <label for="country" class="text-gray-600 sr-only">Pick country to compare</label>
        </div>
        <script>
            let countryInput = () => {
                return {
                    loading: false,
                    search: @json($selectedCountry),
                    selectCountry(event) {
                        this.loading = true
                        window.location.href = '/?country='+this.search
                    }
                }
            }
        </script>
        <div class="mt-1 flex items-center" x-data="countryInput()">
            <div class="relative w-full">
                <input
                    x-model="search"
                    placeholder="Type country name..."
                    list="countries-list"
                    class="form-input py-3 w-full text-gray-800"
                    type="search"
                    @change="selectCountry"
                >
                <datalist id="countries-list">
                    @foreach ($countries as $c)
                        <option
                            value="{{ $c['ISO2'] }}"
                            label="{{ $c['Country'] }}"
                        ></option>
                    @endforeach
                </datalist>

                <div x-cloak class="absolute inset-y-0 flex items-center right-0 mr-16">
                    <span class="spinner" x-show="loading"></span>
                </div>
            </div>
        </div>
    </div>

    @if (! empty($countryData['new_cases_rate']))
        <div class="max-w-md w-full mx-auto px-6 md:px-0">
            @if ($roData['new_cases_rate'] < $countryData['new_cases_rate'])
                <div class="my-4 bg-red-300 rounded p-4 text-red-800">
                    You might be required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $countryData['country'] }}</span>.
                </div>
            @endif

            @if ($roData['new_cases_rate'] >= $countryData['new_cases_rate'])
                <div class="my-4 bg-green-300 rounded p-4 text-green-800">
                    You are <span class="font-bold">NOT</span> required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $countryData['country'] }}</span>.
                </div>
            @endif

            @if (abs($roData['new_cases_rate'] - $countryData['new_cases_rate']) <= 2)
                <div class="my-4 bg-orange-300 rounded p-4 text-orange-800">
                    Be aware that the rates for new cases are pretty close.
                </div>
            @endif
        </div>
    @endif
    <div class="mt-12 container mx-auto px-6 lg:px-0">
        <div class="flex flex-col lg:flex-row items-start justify-center -mx-4">
            @if (! empty($countryData))
                <div class="max-w-md w-full mx-auto px-4">
                    @include('_partials.country-data', ['data' => $countryData])
                </div>
            @endif
            <div class="max-w-md w-full mx-auto px-4 mt-8 lg:mt-0">
                @include('_partials.country-data', ['data' => $roData])
            </div>
        </div>
    </div>
@endsection
