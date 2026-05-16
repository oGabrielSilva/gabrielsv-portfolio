@extends('layouts.tools')

@section('title', 'Contador de Caracteres e Palavras Online')
@section('tool_name', 'Contador de Caracteres')
@section('description', 'Conte caracteres, palavras, linhas, parágrafos e tempo de leitura. Indicadores de limite para SEO (title, description) e Twitter.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="text-counter">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Contador de Caracteres e Palavras</h1>
            <p class="text-gray-400 text-sm sm:text-base">Estatísticas em tempo real do seu texto, com limites de SEO e redes sociais.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="text-input" class="block text-sm font-medium text-gray-300 mb-2">Cole ou digite o texto</label>
            <textarea id="text-input" rows="10" placeholder="Comece a digitar..."
                class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm font-mono"></textarea>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Caracteres</div>
                <div id="stat-chars" class="text-2xl font-bold text-white font-mono">0</div>
            </div>
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Sem espaços</div>
                <div id="stat-chars-nospace" class="text-2xl font-bold text-white font-mono">0</div>
            </div>
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Palavras</div>
                <div id="stat-words" class="text-2xl font-bold text-white font-mono">0</div>
            </div>
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Linhas</div>
                <div id="stat-lines" class="text-2xl font-bold text-white font-mono">0</div>
            </div>
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Parágrafos</div>
                <div id="stat-paragraphs" class="text-2xl font-bold text-white font-mono">0</div>
            </div>
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Leitura</div>
                <div id="stat-reading" class="text-2xl font-bold text-white font-mono">0 s</div>
            </div>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Limites úteis</h2>
            <div class="space-y-3" id="limits-container">
                <div class="limit-row" data-limit="60">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300">Título SEO</span>
                        <span class="text-gray-500"><span class="limit-current">0</span>/60</span>
                    </div>
                    <div class="h-1.5 bg-neutral-700 rounded-full overflow-hidden">
                        <div class="limit-bar h-full bg-bulma-primary transition-all" style="width: 0%"></div>
                    </div>
                </div>
                <div class="limit-row" data-limit="160">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300">Meta description</span>
                        <span class="text-gray-500"><span class="limit-current">0</span>/160</span>
                    </div>
                    <div class="h-1.5 bg-neutral-700 rounded-full overflow-hidden">
                        <div class="limit-bar h-full bg-bulma-primary transition-all" style="width: 0%"></div>
                    </div>
                </div>
                <div class="limit-row" data-limit="280">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-300">Tweet</span>
                        <span class="text-gray-500"><span class="limit-current">0</span>/280</span>
                    </div>
                    <div class="h-1.5 bg-neutral-700 rounded-full overflow-hidden">
                        <div class="limit-bar h-full bg-bulma-primary transition-all" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/text-counter.js'])
    @endpush
@endsection
