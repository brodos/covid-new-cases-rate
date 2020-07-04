<div>
    <div>
        <label for="">De unde te întorci?</label>
        <div class="mt-1 flex items-center">
            <select name="country" class="form-select py-3 text-gray-900" placeholder="Alege o tara" wire:model="country">
                @foreach ($countries as $c)
                    <option value="{{ $c['ISO2'] }}">{{ $c['Country'] }}</option>
                @endforeach
            </select>

            <div class="ml-1" wire:loading>
                <span class="spinner">Loading</span>
            </div>
        </div>
    </div>

    @if ($country && ! $data)
        <div class="mt-4">
            Nu avem date pentru această țară.
        </div>
    @endif
    @if ($country && $data)
        <div class="mt-4 flex justify-between items-center">
            <h3 class="text-4xl">{{ $data['country'] }}</h3>
            <span class="text-3xl ml-4">{{ number_format($data['new_cases_rate'], 2) }}</span>
        </div>

        <div class="border-t border-b border-gray-800 py-6 my-3">
            <ul class="leading-normal text-gray-300">
                <li class="mt-2 first:mt-0">Populație: {{ number_format($data['population'], 0) }}</li>
                <li class="mt-2 first:mt-0">Ultima zi raportată: {{ $data['last_reported_day'] }}</li>

                <li class="mt-2">Cazuri noi în ultimele 14 zile: {{ number_format($data['last_14_days']['confirmed'], 0) }}</li>
                <li class="mt-2">Decese în ultimele 14 zile: {{ number_format($data['last_14_days']['deaths'], 0) }}</li>
                <li class="mt-2">Recuperări în ultimele 14 zile: {{ number_format($data['last_14_days']['recovered'], 0) }}</li>
            </ul>
        </div>
    @endif
</div>
