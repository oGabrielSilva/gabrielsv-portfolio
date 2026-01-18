@extends('layouts.tools')

@section('title', 'Gerador de Slug Online')
@section('tool_name', 'Slugify')
@section('description', 'Converta texto para slug URL-friendly. Ferramenta gratuita para desenvolvedores.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador de Slug</h1>
            <p class="text-gray-400 text-sm sm:text-base">Converta texto para slug URL-friendly</p>
        </div>

        {{-- Generator --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="space-y-4">
                {{-- Input --}}
                <div>
                    <label for="text-input" class="block text-sm font-medium text-gray-300 mb-2">
                        Texto original
                    </label>
                    <textarea id="text-input" rows="3" placeholder="Digite o texto para converter..."
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all resize-none text-sm"></textarea>
                </div>

                {{-- Options --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Separador</label>
                        <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none] w-full">
                            <button id="separator-dropdown" type="button"
                                class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                                aria-haspopup="menu" aria-expanded="false">
                                <span id="separator-label">Hífen (-)</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                                role="menu" aria-orientation="vertical">
                                <button type="button" class="separator-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="-" data-label="Hífen (-)">
                                    Hífen (-)
                                </button>
                                <button type="button" class="separator-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="_" data-label="Underscore (_)">
                                    Underscore (_)
                                </button>
                                <button type="button" class="separator-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="" data-label="Sem separador">
                                    Sem separador
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="separator" value="-">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Formato</label>
                        <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none] w-full">
                            <button id="case-dropdown" type="button"
                                class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                                aria-haspopup="menu" aria-expanded="false">
                                <span id="case-label">Minúsculas</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform"></i>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                                role="menu" aria-orientation="vertical">
                                <button type="button" class="case-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="lowercase" data-label="Minúsculas">
                                    Minúsculas
                                </button>
                                <button type="button" class="case-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="uppercase" data-label="Maiúsculas">
                                    Maiúsculas
                                </button>
                                <button type="button" class="case-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="keep" data-label="Manter formato">
                                    Manter formato
                                </button>
                                <button type="button" class="case-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white transition-colors" data-value="words" data-label="Capitalizar palavras">
                                    Capitalizar palavras
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="case-format" value="lowercase">
                    </div>
                </div>

                {{-- Output --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-300">Slug gerado</label>
                        <button type="button" id="copy-btn"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" id="slug-output" readonly
                            class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-900 text-gray-300 focus:outline-none font-mono text-sm">
                    </div>
                </div>

                {{-- Character count --}}
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Caracteres: <span id="char-count">0</span></span>
                    <span>Tamanho do slug: <span id="slug-length">0</span></span>
                </div>
            </div>
        </div>

        {{-- Examples --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Exemplos rápidos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button type="button" class="example-btn text-left p-3 rounded-lg border border-neutral-700 hover:border-bulma-primary/50 hover:bg-neutral-800 transition-all" data-text="Olá Mundo! Como vai você?">
                    <span class="text-gray-400 text-xs block mb-1">Acentos e espaços</span>
                    <span class="text-white text-sm">Olá Mundo! Como vai você?</span>
                </button>
                <button type="button" class="example-btn text-left p-3 rounded-lg border border-neutral-700 hover:border-bulma-primary/50 hover:bg-neutral-800 transition-all" data-text="Product Name (2024) - Special Edition!">
                    <span class="text-gray-400 text-xs block mb-1">Caracteres especiais</span>
                    <span class="text-white text-sm">Product Name (2024) - Special Edition!</span>
                </button>
                <button type="button" class="example-btn text-left p-3 rounded-lg border border-neutral-700 hover:border-bulma-primary/50 hover:bg-neutral-800 transition-all" data-text="São Paulo é a maior cidade do Brasil">
                    <span class="text-gray-400 text-xs block mb-1">Português</span>
                    <span class="text-white text-sm">São Paulo é a maior cidade do Brasil</span>
                </button>
                <button type="button" class="example-btn text-left p-3 rounded-lg border border-neutral-700 hover:border-bulma-primary/50 hover:bg-neutral-800 transition-all" data-text="¡Hola! ¿Cómo estás? Très bien, merci!">
                    <span class="text-gray-400 text-xs block mb-1">Multi-idioma</span>
                    <span class="text-white text-sm">¡Hola! ¿Cómo estás? Très bien, merci!</span>
                </button>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">O que é um Slug?</h2>
            <div class="space-y-3 text-sm text-gray-400">
                <p>
                    <strong class="text-bulma-primary">Slug</strong> é uma versão URL-friendly de um texto.
                    Remove acentos, espaços e caracteres especiais para criar URLs legíveis e seguras.
                </p>
                <p>
                    <strong>Exemplo:</strong> "Meu Artigo Incrível!" → <code class="text-gray-300 bg-neutral-800 px-1 rounded">meu-artigo-incrivel</code>
                </p>
                <p>
                    <strong>Usos comuns:</strong> URLs de blog, identificadores de produtos, nomes de arquivos, rotas de API.
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
        @vite(['resources/js/tools/slugify.js'])
    @endpush
@endsection
