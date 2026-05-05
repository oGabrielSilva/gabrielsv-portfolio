@extends('layouts.tools')

@section('title', 'JSON Formatter & Validator - Formatador JSON Online')
@section('tool_name', 'JSON Formatter')
@section('description', 'Formate, minifique e valide JSON online gratuitamente. Identifique erros com linha e coluna.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">JSON Formatter & Validator</h1>
            <p class="text-gray-400 text-sm sm:text-base">Formate, minifique e valide seu JSON com facilidade</p>
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
                <label for="indent-size" class="text-sm text-gray-400">Indentação:</label>
                <select id="indent-size"
                    class="py-2 px-3 text-sm rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                    <option value="2" selected>2 espaços</option>
                    <option value="4">4 espaços</option>
                    <option value="tab">Tab</option>
                </select>
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

    {{-- Toast --}}
    <div id="toast"
        class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2 z-50">
        <i data-lucide="check" class="w-4 h-4"></i>
        Copiado!
    </div>

    @push('scripts')
        @vite(['resources/js/tools/json-formatter.js'])
    @endpush
@endsection
