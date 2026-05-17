@extends('layouts.tools')

@section('title', 'JSON Formatter e Validador Online: aponta erro com linha e coluna')
@section('tool_name', 'JSON Formatter')
@section('description', 'Formata, minifica e valida JSON. Quando dá erro, mostra a linha e coluna exatas. Para quando o JSON da API veio quebrado.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">JSON Formatter & Validator</h1>
            <p class="text-gray-400 text-sm sm:text-base">Formata, minifica e valida. Quando dá erro, mostra a linha e coluna exatas.</p>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-wrap gap-2">
            <button type="button" id="format-btn"
                class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                <i data-lucide="indent-increase" class="w-4 h-4"></i>
                Formatar
            </button>
            <button type="button" id="minify-btn"
                class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                <i data-lucide="indent-decrease" class="w-4 h-4"></i>
                Minificar
            </button>
            <button type="button" id="validate-btn"
                class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Validar
            </button>
            <button type="button" id="copy-btn"
                class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                <i data-lucide="copy" class="w-4 h-4"></i>
                Copiar
            </button>
            <button type="button" id="clear-btn"
                class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                Limpar
            </button>

            {{-- Indentação --}}
            <div class="ml-auto flex items-center gap-2">
                <span class="text-sm text-gray-400">Indentação:</span>
                <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]" data-indent-value="2">
                    <button id="indent-dropdown" type="button"
                        class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 transition-all"
                        aria-haspopup="menu" aria-expanded="false">
                        <span id="indent-label">2 espaços</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform"></i>
                    </button>
                    <div
                        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[10rem] bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                        role="menu" aria-labelledby="indent-dropdown">
                        <button type="button" data-indent-option="2"
                            class="indent-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-bulma-primary bg-bulma-primary/10 hover:bg-neutral-700">
                            <span>2 espaços</span>
                        </button>
                        <button type="button" data-indent-option="4"
                            class="indent-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                            <span>4 espaços</span>
                        </button>
                        <button type="button" data-indent-option="tab"
                            class="indent-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                            <span>Tab</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status / Error --}}
        <div id="status-bar" class="hidden rounded-lg p-3 text-sm font-medium flex items-center gap-2">
            <i id="status-icon" class="w-4 h-4"></i>
            <span id="status-text"></span>
        </div>

        {{-- Editor --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-4 py-2 border-b border-neutral-700/50">
                <span class="text-sm text-gray-400">JSON</span>
                <div class="flex items-center gap-3 text-xs text-gray-500" id="stats">
                    <span id="stat-keys">0 chaves</span>
                    <span id="stat-arrays">0 arrays</span>
                    <span id="stat-size">0 bytes</span>
                </div>
            </div>
            <textarea id="json-input"
                class="w-full h-96 p-4 bg-transparent text-gray-200 font-mono text-sm resize-y focus:outline-none placeholder-gray-600"
                placeholder='Cole seu JSON aqui... Ex: {"nome": "Gabriel", "idade": 25}'
                spellcheck="false"></textarea>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/json-formatter.js'])
    @endpush
@endsection
