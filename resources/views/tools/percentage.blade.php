@extends('layouts.tools')

@section('title', 'Calculadora de Porcentagem - Online Grátis')
@section('tool_name', 'Calculadora %')
@section('description', 'Calcule porcentagens, aumentos, descontos e variações percentuais online')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Calculadora de Porcentagem</h1>
            <p class="text-gray-400 text-sm sm:text-base">Calcule porcentagens de forma rápida e fácil</p>
        </div>

        {{-- Calculadoras --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            {{-- Porcentagem de um valor --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i data-lucide="percent" class="w-4 h-4 sm:w-5 sm:h-5 text-bulma-primary"></i>
                    Porcentagem de um valor
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">Quanto é X% de Y?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-400 text-sm">Quanto é</span>
                        <input type="number" id="calc1-percent" value="10" placeholder="10"
                            class="w-16 sm:w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">% de</span>
                        <input type="number" id="calc1-value" value="200" placeholder="200"
                            class="flex-1 min-w-20 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                    </div>
                    <div id="calc1-container" class="p-3 sm:p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-xl sm:text-2xl font-bold text-bulma-primary" id="calc1-result">20</span>
                    </div>
                </div>
            </div>

            {{-- Qual a porcentagem --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500"></i>
                    Qual a porcentagem?
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">X é quantos % de Y?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc2-part" value="50" placeholder="50"
                            class="w-20 sm:w-24 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">é quantos % de</span>
                        <input type="number" id="calc2-total" value="200" placeholder="200"
                            class="flex-1 min-w-20 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                    </div>
                    <div id="calc2-container" class="p-3 sm:p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-xl sm:text-2xl font-bold text-blue-500" id="calc2-result">25%</span>
                    </div>
                </div>
            </div>

            {{-- Aumento percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400"></i>
                    Aumento percentual
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">Valor + X% de aumento</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc3-value" value="100" placeholder="100"
                            class="flex-1 min-w-20 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">+</span>
                        <input type="number" id="calc3-percent" value="15" placeholder="15"
                            class="w-16 sm:w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">%</span>
                    </div>
                    <div id="calc3-container" class="p-3 sm:p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-xl sm:text-2xl font-bold text-emerald-400" id="calc3-result">115</span>
                        <span class="text-xs sm:text-sm text-gray-500 ml-2" id="calc3-diff">(+15)</span>
                    </div>
                </div>
            </div>

            {{-- Desconto percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i data-lucide="trending-down" class="w-4 h-4 sm:w-5 sm:h-5 text-orange-400"></i>
                    Desconto percentual
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">Valor - X% de desconto</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="number" id="calc4-value" value="100" placeholder="100"
                            class="flex-1 min-w-20 max-w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">-</span>
                        <input type="number" id="calc4-percent" value="20" placeholder="20"
                            class="w-16 sm:w-20 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-center text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">%</span>
                    </div>
                    <div id="calc4-container" class="p-3 sm:p-4 bg-neutral-900 rounded-lg text-center">
                        <span class="text-xl sm:text-2xl font-bold text-orange-400" id="calc4-result">80</span>
                        <span class="text-xs sm:text-sm text-gray-500 ml-2" id="calc4-diff">(-20)</span>
                    </div>
                </div>
            </div>

            {{-- Variação percentual --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 lg:col-span-2">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4 flex items-center gap-2">
                    <i data-lucide="line-chart" class="w-4 h-4 sm:w-5 sm:h-5 text-purple-400"></i>
                    Variação percentual
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mb-3 sm:mb-4">Qual a variação % entre dois valores?</p>
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-gray-400 text-sm">De</span>
                        <input type="number" id="calc5-from" value="80" placeholder="80"
                            class="w-24 sm:w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">para</span>
                        <input type="number" id="calc5-to" value="100" placeholder="100"
                            class="w-24 sm:w-32 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                        <span class="text-gray-400 text-sm">=</span>
                        <div id="calc5-container" class="p-2 sm:p-3 bg-neutral-900 rounded-lg">
                            <span class="text-lg sm:text-xl font-bold text-emerald-400" id="calc5-result">+25%</span>
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
