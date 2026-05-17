@extends('layouts.tools')

@section('title', 'Cron Explainer Online: traduz expressão cron e mostra próximas execuções')
@section('tool_name', 'Cron Explainer')
@section('description', 'Cola uma expressão cron (ex: */15 * * * *) e a página explica em português, com as próximas datas de execução. Para montar agendamento sem chutar.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Cron Expression Explainer</h1>
            <p class="text-gray-400 text-sm sm:text-base">Cola a expressão e a página explica em português, com as próximas datas que ela vai rodar.</p>
        </div>

        {{-- Input --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="cron-input" class="block text-sm font-medium text-gray-300 mb-2">Expressão Cron</label>
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" id="cron-input" value="*/5 * * * *"
                    class="flex-1 py-3 px-4 font-mono text-lg rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all"
                    placeholder="* * * * *" spellcheck="false">
                <button type="button" id="explain-btn"
                    class="py-3 px-6 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                    <i data-lucide="message-circle-question" class="w-4 h-4"></i>
                    Explicar
                </button>
            </div>

            {{-- Field labels --}}
            <div class="grid grid-cols-5 gap-2 mt-4 text-center text-xs text-gray-500">
                <span>Minuto</span>
                <span>Hora</span>
                <span>Dia (mês)</span>
                <span>Mês</span>
                <span>Dia (semana)</span>
            </div>
        </div>

        {{-- Presets --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Presets Rápidos</h2>
            <div class="flex flex-wrap gap-2" id="presets">
                <button data-cron="* * * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">A cada minuto</button>
                <button data-cron="*/5 * * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">A cada 5 min</button>
                <button data-cron="*/15 * * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">A cada 15 min</button>
                <button data-cron="0 * * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">A cada hora</button>
                <button data-cron="0 0 * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Meia-noite</button>
                <button data-cron="0 12 * * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Meio-dia</button>
                <button data-cron="0 9 * * 1-5" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Dias úteis 9h</button>
                <button data-cron="0 0 * * 0" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Semanal (dom)</button>
                <button data-cron="0 0 1 * *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Mensal (dia 1)</button>
                <button data-cron="0 0 1 1 *" class="preset-btn py-1.5 px-3 text-xs rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-bulma-primary/20 hover:text-bulma-primary hover:border-bulma-primary/50 transition-all">Anual (1 jan)</button>
            </div>
        </div>

        {{-- Resultado --}}
        <div id="result-container" class="space-y-4">
            {{-- Explicação --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-white mb-3">Explicação</h2>
                <p id="explanation" class="text-gray-300 text-base leading-relaxed"></p>
            </div>

            {{-- Próximas execuções --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-white mb-3">Próximas Execuções</h2>
                <div id="next-runs" class="space-y-2"></div>
            </div>

            {{-- Referência --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-white mb-3">Referência Rápida</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase border-b border-neutral-700">
                            <tr>
                                <th class="py-2 pr-4">Símbolo</th>
                                <th class="py-2 pr-4">Significado</th>
                                <th class="py-2">Exemplo</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300">
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 pr-4 font-mono text-bulma-primary">*</td>
                                <td class="py-2 pr-4">Qualquer valor</td>
                                <td class="py-2 font-mono text-gray-500">* (todo minuto)</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 pr-4 font-mono text-bulma-primary">,</td>
                                <td class="py-2 pr-4">Lista de valores</td>
                                <td class="py-2 font-mono text-gray-500">1,3,5</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 pr-4 font-mono text-bulma-primary">-</td>
                                <td class="py-2 pr-4">Intervalo</td>
                                <td class="py-2 font-mono text-gray-500">1-5 (de 1 a 5)</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-mono text-bulma-primary">*/n</td>
                                <td class="py-2 pr-4">A cada N</td>
                                <td class="py-2 font-mono text-gray-500">*/15 (a cada 15)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/cron.js'])
    @endpush
@endsection
