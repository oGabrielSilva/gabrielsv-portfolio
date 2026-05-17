@extends('layouts.tools')

@section('title', 'Conversor de Unidades CSS Online (px, rem, em, %, pt, cm)')
@section('tool_name', 'Conversor de Unidades')
@section('description', 'Quanto é 24px em rem? Digita o valor em uma unidade e as outras aparecem na hora. Root font-size editável para quem usa 10px, 12px ou outro valor.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="unit-converter">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Conversor de Unidades CSS</h1>
            <p class="text-gray-400 text-sm sm:text-base">Digita o valor em uma unidade, as outras aparecem na hora. Root font-size editável.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="base-size" class="block text-sm font-medium text-gray-300 mb-2">Tamanho base (root font-size)</label>
            <div class="flex items-center gap-3">
                <div class="py-1.5 px-3 w-32 rounded-lg border border-neutral-600 bg-neutral-700"
                    data-hs-input-number='{"min": 1, "max": 100, "step": 1}'>
                    <div class="w-full flex justify-between items-center gap-x-2">
                        <input id="base-size" type="number" value="16"
                            class="w-12 p-0 bg-transparent border-0 text-white text-center font-mono focus:ring-0 focus:outline-none [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                            style="-moz-appearance: textfield;"
                            aria-roledescription="Number field"
                            data-hs-input-number-input>
                        <div class="flex items-center gap-x-1">
                            <button type="button" tabindex="-1" aria-label="Diminuir" data-hs-input-number-decrement
                                class="size-6 inline-flex justify-center items-center rounded-md border border-neutral-600 bg-neutral-800 text-gray-300 hover:bg-neutral-600 hover:text-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                                <i data-lucide="minus" class="w-3 h-3"></i>
                            </button>
                            <button type="button" tabindex="-1" aria-label="Aumentar" data-hs-input-number-increment
                                class="size-6 inline-flex justify-center items-center rounded-md border border-neutral-600 bg-neutral-800 text-gray-300 hover:bg-neutral-600 hover:text-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <span class="text-sm text-gray-500">px (padrão dos browsers é 16px)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="unit-grid">
            @php
                $units = [
                    'px' => 'Pixels',
                    'rem' => 'REM',
                    'em' => 'EM',
                    'percent' => 'Porcentagem',
                    'pt' => 'Pontos',
                    'cm' => 'Centímetros',
                ];
            @endphp
            @foreach($units as $key => $label)
                <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                    <label class="block text-xs font-medium text-bulma-primary uppercase tracking-wider mb-2">{{ $label }} ({{ $key === 'percent' ? '%' : $key }})</label>
                    <input type="number" data-unit="{{ $key }}" step="any"
                        class="unit-input w-full py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all font-mono">
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/unit-converter.js'])
    @endpush
@endsection
