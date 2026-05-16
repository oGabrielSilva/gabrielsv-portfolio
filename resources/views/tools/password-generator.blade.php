@extends('layouts.tools')

@section('title', 'Gerador de Senhas Fortes - Password Generator Online')
@section('tool_name', 'Gerador de Senhas')
@section('description', 'Gere senhas fortes e seguras com opções customizáveis de comprimento e caracteres')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador de Senhas Fortes</h1>
            <p class="text-gray-400 text-sm sm:text-base">Gere senhas seguras usando aleatoriedade criptográfica</p>
        </div>

        {{-- Senha gerada --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <input type="text" id="password-display" readonly
                        class="w-full py-3 px-4 font-mono text-lg sm:text-xl bg-neutral-900 border border-neutral-700 rounded-lg text-white focus:outline-none cursor-text select-all"
                        value="">
                </div>
                <button type="button" id="copy-btn"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all shrink-0">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                    Copiar
                </button>
                <button type="button" id="refresh-btn"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all shrink-0">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Strength bar --}}
            <div class="mt-4">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-500">Força da senha</span>
                    <span id="strength-label" class="text-xs font-medium">—</span>
                </div>
                <div class="w-full h-2 bg-neutral-700 rounded-full overflow-hidden">
                    <div id="strength-bar" class="h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>

        {{-- Configurações --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-5">
            {{-- Comprimento --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="length-slider" class="text-sm font-medium text-gray-300">Comprimento</label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="length-input" min="4" max="128" value="16"
                            class="w-16 py-1 px-2 text-sm text-center font-mono rounded border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-1 focus:ring-bulma-primary">
                    </div>
                </div>
                <input type="range" id="length-slider" min="4" max="128" value="16"
                    class="w-full h-2 bg-neutral-700 rounded-lg appearance-none cursor-pointer accent-bulma-primary">
            </div>

            {{-- Toggles --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center justify-between p-3 rounded-lg border border-neutral-700 bg-neutral-800/50 cursor-pointer hover:border-neutral-600 transition-all">
                    <span class="text-sm text-gray-300">Maiúsculas (A-Z)</span>
                    <input type="checkbox" id="opt-upper" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                </label>
                <label class="flex items-center justify-between p-3 rounded-lg border border-neutral-700 bg-neutral-800/50 cursor-pointer hover:border-neutral-600 transition-all">
                    <span class="text-sm text-gray-300">Minúsculas (a-z)</span>
                    <input type="checkbox" id="opt-lower" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                </label>
                <label class="flex items-center justify-between p-3 rounded-lg border border-neutral-700 bg-neutral-800/50 cursor-pointer hover:border-neutral-600 transition-all">
                    <span class="text-sm text-gray-300">Números (0-9)</span>
                    <input type="checkbox" id="opt-numbers" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                </label>
                <label class="flex items-center justify-between p-3 rounded-lg border border-neutral-700 bg-neutral-800/50 cursor-pointer hover:border-neutral-600 transition-all">
                    <span class="text-sm text-gray-300">Símbolos (!@#$%...)</span>
                    <input type="checkbox" id="opt-symbols" checked
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                </label>
            </div>

            {{-- Excluir ambíguos --}}
            <label class="flex items-center justify-between p-3 rounded-lg border border-neutral-700 bg-neutral-800/50 cursor-pointer hover:border-neutral-600 transition-all">
                <div>
                    <span class="text-sm text-gray-300">Excluir caracteres ambíguos</span>
                    <span class="text-xs text-gray-500 block mt-0.5">Remove: 0 O o l 1 I | ` ' " ~ , ; : . &lt; &gt;</span>
                </div>
                <input type="checkbox" id="opt-exclude-ambiguous"
                    class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
            </label>
        </div>

        {{-- Múltiplas senhas --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">Gerar Múltiplas</h2>
                <div class="flex items-center gap-2">
                    <input type="number" id="multi-count" min="2" max="50" value="5"
                        class="w-16 py-1 px-2 text-sm text-center font-mono rounded border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-1 focus:ring-bulma-primary">
                    <button type="button" id="generate-multi-btn"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="list" class="w-4 h-4"></i>
                        Gerar
                    </button>
                </div>
            </div>
            <div id="multi-list" class="space-y-2 hidden"></div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/password-generator.js'])
    @endpush
@endsection
