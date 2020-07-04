@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center h-full">
        <div class="max-w-md w-full mx-auto">
            <div class="flex justify-between items-start leading-none">
                <div>
                    <h3 class="text-4xl">{{ $data['country'] }}</h3>
                    <p class="mt-2 text-sm text-gray-500">Last reported day: {{ \Carbon\Carbon::parse($data['last_reported_day'])->format('F j, Y') }}</p>
                </div>
                <span class="text-3xl ml-4">{{ number_format($data['new_cases_rate'], 2) }}</span>
            </div>

            <div class="border-t border-b border-gray-800 py-6 my-3">
                <ul class="leading-normal text-gray-300">
                    <li class="flex justify-between">
                        <span>Population</span>
                        <span>{{ number_format($data['population'], 0) }}</span>
                    </li>
                    <li class="mt-6 font-bold text-xs uppercase text-gray-600">Last 14 days</li>
                    <li class="mt-1 flex justify-between">
                        <span>New cases</span>
                        <span>{{ number_format($data['last_14_days']['confirmed'], 0) }}</span>
                    </li>
                    <li class="mt-1 flex justify-between">
                        <span>Deaths</span>
                        <span>{{ number_format($data['last_14_days']['deaths'], 0) }}</span>
                    </li>
                    <li class="mt-1 flex justify-between">
                        <span>Recoveries</span>
                        <span>{{ number_format($data['last_14_days']['recovered'], 0) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 max-w-md w-full mx-auto">
            <livewire:new-cases-rate-by-country :romania="$data" />
        </div>
    </div>
@endsection
