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
