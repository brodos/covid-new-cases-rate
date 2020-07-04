@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center h-full">
        <div class="max-w-xl w-full mx-auto">
            <div class="flex justify-between items-center">
                <h3 class="text-4xl">{{ $data['country'] }}</h3>
                <span class="text-3xl ml-4">{{ number_format($data['new_cases_rate'], 2) }}</span>
            </div>

            <div class="border-t border-b border-gray-800 py-6 my-3">
                <ul class="leading-normal text-gray-300">
                    <li class="mt-2 first:mt-0">Population: {{ number_format($data['population'], 0) }}</li>
                    <li class="mt-2 first:mt-0">Last reported day: {{ $data['last_reported_day'] }}</li>

                    <li class="mt-2">14 days new cases: {{ number_format($data['last_14_days']['confirmed'], 0) }}</li>
                    <li class="mt-2">14 days deaths: {{ number_format($data['last_14_days']['deaths'], 0) }}</li>
                    <li class="mt-2">14 days recovered: {{ number_format($data['last_14_days']['recovered'], 0) }}</li>
                </ul>
            </div>
        </div>

        <div class="mt-12 max-w-xl w-full mx-auto">
            <livewire:new-cases-rate-by-country />
        </div>
    </div>
@endsection
