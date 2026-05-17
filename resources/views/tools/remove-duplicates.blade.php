@extends('layouts.tools')

@section('title', 'Remover Linhas Duplicadas Online: limpa lista de e-mails, leads e IDs')
@section('tool_name', 'Remover Duplicadas')
@section('description', 'Cola uma lista (e-mails, leads, IDs, qualquer coisa em linhas) e a página devolve sem repetição. Opção para ignorar case e ordenar alfabeticamente.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="remove-duplicates">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Remover Linhas Duplicadas</h1>
            <p class="text-gray-400 text-sm sm:text-base">Cola uma lista (e-mails, leads, IDs) e a página devolve sem repetição.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-4">
            <div>
                <label for="dup-input" class="block text-sm font-medium text-gray-300 mb-2">Texto original</label>
                <textarea id="dup-input" rows="8" placeholder="Cole uma linha por entrada..."
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm font-mono"></textarea>
            </div>

            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="opt-ignore-case" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                    <span class="text-sm text-gray-300">Ignorar maiúsc./minúsc.</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="opt-trim" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                    <span class="text-sm text-gray-300">Ignorar espaços extras</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="opt-skip-empty" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                    <span class="text-sm text-gray-300">Remover linhas vazias</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="opt-sort"
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                    <span class="text-sm text-gray-300">Ordenar A-Z</span>
                </label>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-300">Resultado</label>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span><span id="stat-original">0</span> linhas →
                            <span id="stat-result">0</span> únicas
                            (<span id="stat-removed" class="text-amber-400">0 removidas</span>)</span>
                        <button type="button" id="copy-btn"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                    </div>
                </div>
                <textarea id="dup-output" rows="8" readonly
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-900 text-gray-300 font-mono text-sm focus:outline-none"></textarea>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/remove-duplicates.js'])
    @endpush
@endsection
