<div>
    <div>
        <label for="country" class="text-gray-600">Pick country to compare</label>
        <div class="mt-1 flex items-center">
            <input
                name="country"
                class="form-input w-full py-3 text-gray-900"
                placeholder="Type country name..."
                wire:model.lazy="country"
                list="countries"
            />
            <datalist id="countries">
                @foreach ($countries as $c)
                    <option value="{{ $c['ISO2'] }}" label="{{ $c['Country'] }}"></option>
                @endforeach
            </datalist>

            <div class="ml-1" wire:loading>
                <span class="spinner">Loading</span>
            </div>
        </div>
    </div>

    @if ($country && ! $data)
        <div class="mt-4">
            No data found for this country.
        </div>
    @endif

    @if ($country && $data)
        <div class="mt-4 flex justify-between items-start leading-none">
            <div>
                <h3 class="text-4xl">{{ $data['country'] }}</h3>
                <p class="mt-2 text-sm text-gray-600">Last reported day: {{ \Carbon\Carbon::parse($data['last_reported_day'])->format('F j, Y') }}</p>
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

        @if ($romania['new_cases_rate'] < $data['new_cases_rate'])
            <div class="mt-4 bg-red-300 rounded p-4 text-red-800">
                You are required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $data['country'] }}</span>.
            </div>
        @endif

        @if ($data['new_cases_rate'] < $romania['new_cases_rate']  && $data['new_cases_rate'] - $romania['new_cases_rate']  < 2)
            <div class="mt-4 bg-orange-300 rounded p-4 text-orange-800">
                Be aware that the rates for new cases are pretty close.
            </div>
        @endif

        @if ($romania['new_cases_rate'] >= $data['new_cases_rate'])
            <div class="mt-4 bg-green-300 rounded p-4 text-green-800">
                You are <span class="font-bold">NOT</span> required to self-isolate for 14 days when coming to Romania from <span class="font-bold">{{ $data['country'] }}</span>.
            </div>
        @endif
    @endif
</div>
