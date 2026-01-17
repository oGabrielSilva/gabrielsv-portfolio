@extends('layouts.tools')

@section('title', 'Calculadora de Porcentagem - Online Grátis')
@section('tool_name', 'Calculadora %')
@section('description', 'Calcule porcentagens, aumentos, descontos e variações percentuais online')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Calculadora de Porcentagem</h1>
            <p class="text-gray-400">Calcule porcentagens de forma rápida e fácil</p>
        </div>

        {{-- Calculadoras --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Porcentagem de um valor --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fa-solid fa-percent text-bulma-primary mr-2"></i>
                    Porcentagem de um valor
                </h2>
                <p class="text-sm text-gray-400 mb-4">Quanto é X% de Y?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-400">Quanto é</span>
                        <input type="number" id="calc1-percent" value="10" placeholder="10"
                            class="w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">% de</span>
                        <input type="number" id="calc1-value" value="200" placeholder="200"
                            class="flex-1 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">?</span>
                    </div>
                    <div id="calc1-container" class="p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-2xl font-bold text-bulma-primary" id="calc1-result">20</span>
                    </div>
                </div>
            </div>

            {{-- Qual a porcentagem --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fa-solid fa-question text-blue-500 mr-2"></i>
                    Qual a porcentagem?
                </h2>
                <p class="text-sm text-gray-400 mb-4">X é quantos % de Y?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc2-part" value="50" placeholder="50"
                            class="w-24 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">é quantos % de</span>
                        <input type="number" id="calc2-total" value="200" placeholder="200"
                            class="flex-1 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">?</span>
                    </div>
                    <div id="calc2-container" class="p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-2xl font-bold text-blue-500" id="calc2-result">25%</span>
                    </div>
                </div>
            </div>

            {{-- Aumento percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fa-solid fa-arrow-trend-up text-emerald-400 mr-2"></i>
                    Aumento percentual
                </h2>
                <p class="text-sm text-gray-400 mb-4">Valor + X% de aumento</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc3-value" value="100" placeholder="100"
                            class="flex-1 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">+</span>
                        <input type="number" id="calc3-percent" value="15" placeholder="15"
                            class="w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">%</span>
                    </div>
                    <div id="calc3-container" class="p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-2xl font-bold text-emerald-400" id="calc3-result">115</span>
                        <span class="text-sm text-gray-500 ml-2" id="calc3-diff">(+15)</span>
                    </div>
                </div>
            </div>

            {{-- Desconto percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fa-solid fa-arrow-trend-down text-orange-400 mr-2"></i>
                    Desconto percentual
                </h2>
                <p class="text-sm text-gray-400 mb-4">Valor - X% de desconto</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc4-value" value="100" placeholder="100"
                            class="flex-1 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">-</span>
                        <input type="number" id="calc4-percent" value="20" placeholder="20"
                            class="w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">%</span>
                    </div>
                    <div id="calc4-container" class="p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-2xl font-bold text-orange-400" id="calc4-result">80</span>
                        <span class="text-sm text-gray-500 ml-2" id="calc4-diff">(-20)</span>
                    </div>
                </div>
            </div>

            {{-- Variação percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6 md:col-span-2">
                <h2 class="text-lg font-semibold text-white mb-4">
                    <i class="fa-solid fa-chart-line text-purple-400 mr-2"></i>
                    Variação percentual
                </h2>
                <p class="text-sm text-gray-400 mb-4">Qual a variação % entre dois valores?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-400">De</span>
                        <input type="number" id="calc5-from" value="80" placeholder="80"
                            class="w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">para</span>
                        <input type="number" id="calc5-to" value="100" placeholder="100"
                            class="w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400">= variação de</span>
                        <div id="calc5-container" class="p-3 bg-neutral-900 rounded-lg">
                            <span class="text-xl font-bold text-emerald-400" id="calc5-result">+25%</span>
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