<div>
    <div>
        <div class="flex justify-between">
            <label for="country" class="text-gray-600">Pick country to compare</label>

        </div>
        <div class="mt-1 flex items-center">
            <div class="relative w-full">
                <select class="form-select py-3 w-full" name="country" id="country" wire:model="country">
                    @foreach ($countries as $c)
                        <option value="{{ $c['ISO2'] }}" label="{{ $c['Country'] }}"></option>
                    @endforeach
                </select>

                <div class="absolute inset-y-0 flex items-center right-0 mr-12">
                    <span class="spinner" wire:loading></span>
                </div>
            </div>
        </div>
    </div>

    @if ($country && ! $data)
        <div class="mt-4">
            No data found for this country.
        </div>
    @endif

    @if ($country && $data)
        <div class="border-b border-gray-800 pb-3 mt-4 flex justify-between items-start leading-none">
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
                <svg class="mt-1 country-sparkline" width="85" height="30" stroke-width="3" stroke="#4299E1" fill="#63B3ED"></svg>
            </div>
        </div>

        @if ($romania['new_cases_rate'] < $data['new_cases_rate'])
            <div class="my-4 bg-red-300 rounded p-4 text-red-800">
                You are required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $data['country'] }}</span>.
            </div>
        @endif

        @if ($romania['new_cases_rate'] >= $data['new_cases_rate'])
            <div class="my-4 bg-green-300 rounded p-4 text-green-800">
                You are <span class="font-bold">NOT</span> required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $data['country'] }}</span>.
            </div>
        @endif

        @if (abs($romania['new_cases_rate'] - $data['new_cases_rate']) <= 2)
            <div class="my-4 bg-orange-300 rounded p-4 text-orange-800">
                Be aware that the rates for new cases are pretty close.
            </div>
        @endif

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
    @endif
</div>

@push('scripts')
    <script src="https://unpkg.com/@fnando/sparkline@0.3.10/dist/sparkline.js"></script>
    <script>
        @if (isset($data['trend']) && $data['trend']->isNotEmpty())
            sparkline.sparkline(document.querySelector('.country-sparkline'), {{ $data['trend']->pluck('new_cases_rate') }});
        @endif
        window.livewire.on('dataFetched', trend => {
            if (trend.length) {
                sparkline.sparkline(document.querySelector('.country-sparkline'), trend);
            }
        })
    </script>
@endpush
