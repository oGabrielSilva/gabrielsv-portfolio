@extends('layouts.tools')

@section('title', 'Calculadora de Porcentagem - Online Grátis')
@section('tool_name', 'Calculadora %')
@section('description', 'Calcule porcentagens, aumentos, descontos e variações percentuais online')

@section('content')
    <div class="space-y-6 md:space-y-8 px-2 md:px-0">
        {{-- Header --}}
        <div class="px-2 md:px-0">
            <h1 class="text-xl md:text-2xl font-bold text-white mb-2">Calculadora de Porcentagem</h1>
            <p class="text-sm md:text-base text-gray-400">Calcule porcentagens de forma rápida e fácil</p>
        </div>

        {{-- Calculadoras --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

            {{-- 1. Porcentagem de um valor --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-5 md:p-6 flex flex-col h-full">
                <h2 class="text-base md:text-lg font-semibold text-white mb-1 flex items-center">
                    <i class="fa-solid fa-percent text-bulma-primary mr-2 w-5"></i>
                    Porcentagem de um valor
                </h2>
                <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">Quanto é X% de Y?</p>

                <div class="mt-auto space-y-4">
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-3">
                        <span class="text-gray-300 text-sm">Quanto é</span>
                        <input type="number" id="calc1-percent" value="10"
                            class="w-20 py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:ring-2 focus:ring-bulma-primary outline-none">
                        <span class="text-gray-300 text-sm">% de</span>
                        <input type="number" id="calc1-value" value="200"
                            class="flex-1 min-w-[100px] py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                    </div>

                    <div id="calc1-container"
                        class="p-4 bg-neutral-900/80 rounded-xl border border-neutral-700/30 text-center">
                        <span class="text-xs text-gray-500 block mb-1 uppercase">Resultado</span>
                        <span class="text-3xl font-bold text-bulma-primary" id="calc1-result">20</span>
                    </div>
                </div>
            </div>

            {{-- 2. Qual a porcentagem --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-5 md:p-6 flex flex-col h-full">
                <h2 class="text-base md:text-lg font-semibold text-white mb-1 flex items-center">
                    <i class="fa-solid fa-question text-blue-400 mr-2 w-5"></i>
                    Qual a porcentagem?
                </h2>
                <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">X é quantos % de Y?</p>

                <div class="mt-auto space-y-4">
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-3">
                        <input type="number" id="calc2-part" value="50"
                            class="w-24 py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                        <span class="text-gray-300 text-sm">é quantos % de</span>
                        <input type="number" id="calc2-total" value="200"
                            class="flex-1 min-w-[100px] py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                    </div>

                    <div id="calc2-container"
                        class="p-4 bg-neutral-900/80 rounded-xl border border-neutral-700/30 text-center">
                        <span class="text-xs text-gray-500 block mb-1 uppercase">Resultado</span>
                        <span class="text-3xl font-bold text-blue-400" id="calc2-result">25%</span>
                    </div>
                </div>
            </div>

            {{-- 3. Aumento percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-5 md:p-6 flex flex-col h-full">
                <h2 class="text-base md:text-lg font-semibold text-white mb-1 flex items-center">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-400 mr-2 w-5"></i>
                    Aumento
                </h2>
                <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">Valor + X% de aumento</p>

                <div class="mt-auto space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="number" id="calc3-value" value="100"
                            class="flex-1 py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                        <span class="text-emerald-400 font-bold">+</span>
                        <div class="relative w-24">
                            <input type="number" id="calc3-percent" value="15"
                                class="w-full py-2.5 px-3 pr-8 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:ring-2 focus:ring-bulma-primary outline-none">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <div id="calc3-container"
                        class="p-4 bg-neutral-900/80 rounded-xl border border-neutral-700/30 text-center">
                        <span class="text-xs text-gray-500 block mb-1 uppercase">Valor Final</span>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-3xl font-bold text-emerald-400" id="calc3-result">115</span>
                            <span class="text-sm font-medium text-emerald-500/80" id="calc3-diff">(+15)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Desconto percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-5 md:p-6 flex flex-col h-full">
                <h2 class="text-base md:text-lg font-semibold text-white mb-1 flex items-center">
                    <i class="fa-solid fa-arrow-trend-down text-orange-400 mr-2 w-5"></i>
                    Desconto
                </h2>
                <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">Valor - X% de desconto</p>

                <div class="mt-auto space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="number" id="calc4-value" value="100"
                            class="flex-1 py-2.5 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                        <span class="text-orange-400 font-bold">-</span>
                        <div class="relative w-24">
                            <input type="number" id="calc4-percent" value="20"
                                class="w-full py-2.5 px-3 pr-8 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:ring-2 focus:ring-bulma-primary outline-none">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                        </div>
                    </div>

                    <div id="calc4-container"
                        class="p-4 bg-neutral-900/80 rounded-xl border border-neutral-700/30 text-center">
                        <span class="text-xs text-gray-500 block mb-1 uppercase">Valor Final</span>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-3xl font-bold text-orange-400" id="calc4-result">80</span>
                            <span class="text-sm font-medium text-orange-500/80" id="calc4-diff">(-20)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Variação percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-5 md:p-6 md:col-span-2">
                <h2 class="text-base md:text-lg font-semibold text-white mb-1 flex items-center">
                    <i class="fa-solid fa-chart-line text-purple-400 mr-2 w-5"></i>
                    Variação Percentual
                </h2>
                <p class="text-xs text-gray-500 mb-6 uppercase tracking-wider">Qual a diferença em % entre dois valores?</p>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    <div class="sm:col-span-5 flex items-center gap-3">
                        <span class="text-gray-400 text-sm w-8">De</span>
                        <input type="number" id="calc5-from" value="80"
                            class="flex-1 py-2.5 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                    </div>

                    <div class="sm:col-span-5 flex items-center gap-3">
                        <span class="text-gray-400 text-sm w-8">Para</span>
                        <input type="number" id="calc5-to" value="100"
                            class="flex-1 py-2.5 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <div id="calc5-container"
                            class="py-2.5 px-4 bg-neutral-900 rounded-lg border border-neutral-700/50 text-center">
                            <span id="calc5-result" class="text-lg font-bold text-emerald-400">+25%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/percentage-calculator.js'])
    @endpush
@endsection