@extends('layouts.app')

@section('content')
    <div class="px-6 lg:px-0 max-w-md mx-auto w-full">
        <h1 class="text-center text-3xl md:text-4xl">
            COVID-19 new cases rate in the last 14 days per 100k inhabitants
        </h1>
    </div>
    <div class="mt-8 container mx-auto flex flex-col lg:flex-row items-center justify-center px-6 lg:px-0">
        <div class="max-w-md w-full mx-auto">
            <div class="flex justify-between items-start leading-none">
                <div>
                    <h3 class="text-4xl flex items-center">
                        <span>{{ $data['country'] }}</span>
                        @if ($data['flag'])
                            <span class="ml-4">
                                <img class="h-6" src="{{ $data['flag'] }}" alt="{{ $data['country'] }} flag">
                            </span>
                        @endif
                    </h3>
                    <p class="mt-2 text-sm text-gray-600">Last reported day: {{ \Carbon\Carbon::parse($data['last_reported_day'])->format('F j, Y') }}</p>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-3xl ml-4">{{ number_format($data['new_cases_rate'], 2) }}</span>
                    <svg class="mt-1 sparkline" width="85" height="30" stroke-width="3" stroke="#4299E1" fill="#63B3ED"></svg>
                </div>
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

        <div class="mt-12 md:max-w-md w-full mx-auto">
            <livewire:new-cases-rate-by-country :romania="$data" />
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://unpkg.com/@fnando/sparkline@0.3.10/dist/sparkline.js"></script>
    <script>
        window.onload = () => {
            sparkline.sparkline(document.querySelector('.sparkline'), {{ $data['trend']->pluck('new_cases_rate') }});
        }
    </script>
@endpush
