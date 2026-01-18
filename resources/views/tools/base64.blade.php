@extends('layouts.tools')

@section('title', 'Codificador e Decodificador Base64 Online')
@section('tool_name', 'Base64')
@section('description', 'Codifique e decodifique texto em Base64 online gratuitamente. Ferramenta para desenvolvedores.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Codificador e Decodificador Base64</h1>
            <p class="text-gray-400 text-sm sm:text-base">Converta texto para Base64 ou decodifique Base64 para texto</p>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-neutral-700">
            <nav class="flex gap-x-1" aria-label="Tabs" role="tablist">
                <button type="button"
                    class="tab-btn py-3 px-4 text-sm font-medium border-b-2 transition-colors border-bulma-primary text-bulma-primary"
                    data-tab="encode" role="tab">
                    Encode
                </button>
                <button type="button"
                    class="tab-btn py-3 px-4 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-400 hover:text-gray-300"
                    data-tab="decode" role="tab">
                    Decode
                </button>
            </nav>
        </div>

        {{-- Encode Section --}}
        <div id="encode-section" class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Texto para Base64</h2>

            <div class="space-y-4">
                <div>
                    <label for="encode-input" class="block text-sm font-medium text-gray-300 mb-2">
                        Texto original
                    </label>
                    <textarea id="encode-input" rows="4" placeholder="Digite o texto para codificar..."
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all resize-none font-mono text-sm"></textarea>
                </div>

                <button type="button" id="encode-btn"
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Codificar</span>
                </button>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-300">Resultado Base64</label>
                        <button type="button" id="copy-encode-btn"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                    </div>
                    <textarea id="encode-output" rows="4" readonly
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-900 text-gray-300 focus:outline-none resize-none font-mono text-sm"></textarea>
                </div>
            </div>
        </div>

        {{-- Decode Section --}}
        <div id="decode-section" class="hidden bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Base64 para Texto</h2>

            <div class="space-y-4">
                <div>
                    <label for="decode-input" class="block text-sm font-medium text-gray-300 mb-2">
                        Texto Base64
                    </label>
                    <textarea id="decode-input" rows="4" placeholder="Cole o texto Base64 aqui..."
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all resize-none font-mono text-sm"></textarea>
                </div>

                <button type="button" id="decode-btn"
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                    <i data-lucide="unlock" class="w-4 h-4"></i>
                    <span>Decodificar</span>
                </button>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-300">Texto decodificado</label>
                        <button type="button" id="copy-decode-btn"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                    </div>
                    <textarea id="decode-output" rows="4" readonly
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-900 text-gray-300 focus:outline-none resize-none font-mono text-sm"></textarea>
                </div>

                {{-- Error message --}}
                <div id="decode-error" class="hidden py-3 px-4 rounded-lg bg-red-500/10 text-red-400 border border-red-500/20 text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 inline-block mr-1"></i>
                    <span id="decode-error-message">Texto Base64 inválido</span>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Sobre Base64</h2>
            <div class="space-y-3 text-sm text-gray-400">
                <p>
                    <strong class="text-bulma-primary">Base64</strong> é um esquema de codificação que converte dados binários em texto ASCII.
                    É amplamente usado para transmitir dados em meios que só suportam texto.
                </p>
                <p>
                    <strong>Usos comuns:</strong> Imagens em CSS/HTML (data URIs), tokens de autenticação, anexos de email,
                    armazenamento de dados binários em JSON/XML.
                </p>
            </div>
        </div>

        {{-- Toast --}}
        <div id="toast"
            class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2 z-50">
            <i data-lucide="check" class="w-4 h-4"></i>
            <span id="toast-message">Copiado!</span>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/base64.js'])
    @endpush
@endsection
