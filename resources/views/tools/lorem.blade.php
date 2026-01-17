@extends('layouts.tools')

@section('title', 'Lorem Ipsum Generator - Gerador Online')
@section('tool_name', 'Lorem Ipsum')
@section('description', 'Gere textos Lorem Ipsum para seus projetos de design e desenvolvimento')

@section('content')
    <div class="space-y-4 md:space-y-6 px-2 md:px-0">
        {{-- Header --}}
        <div class="px-2 md:px-0">
            <h1 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Lorem Ipsum Generator</h1>
            <p class="text-sm md:text-base text-gray-400">Gere textos placeholder para seus projetos</p>
        </div>

        {{-- Configurações --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {{-- Tipo --}}
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                    <div class="hs-dropdown relative w-full [--strategy:absolute] [--adaptive:none]">
                        <button id="lorem-type-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all">
                            <span id="lorem-type-label">{{ $types[$type]['label'] }}</span>
                            <svg class="hs-dropdown-open:rotate-180 size-4 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div
                            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50">
                            @foreach($types as $typeKey => $typeData)
                                <button type="button" data-lorem-type="{{ $typeKey }}"
                                    class="w-full flex flex-col items-start gap-y-0.5 py-2 px-3 rounded-lg text-sm transition-colors text-gray-300 hover:bg-neutral-700 hover:text-white {{ $type === $typeKey ? 'bg-bulma-primary/10 text-bulma-primary' : '' }}">
                                    <span class="font-medium">{{ $typeData['label'] }}</span>
                                    <span
                                        class="text-[10px] md:text-xs text-gray-500 uppercase tracking-tight">{{ $typeData['description'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Quantidade --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-300 mb-2">Quantidade</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $quantity }}" min="1" max="50"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all">
                </div>

                {{-- Botão Gerar - No Mobile ocupa linha inteira ou se junta ao grid --}}
                <div class="flex items-end sm:col-span-2 md:col-span-1">
                    <button type="button" id="generate-btn"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 active:scale-[0.98] transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles" id="generate-icon"></i>
                        <span id="generate-text">Gerar Texto</span>
                    </button>
                </div>
            </div>

            {{-- Opções --}}
            <div class="mt-4 pt-4 border-t border-neutral-700/50">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" id="start-with-lorem" {{ $startWithLorem ? 'checked' : '' }}
                            class="peer w-5 h-5 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0 transition-all">
                    </div>
                    <span class="text-sm text-gray-300 group-hover:text-white transition-colors">Começar com "Lorem
                        ipsum..."</span>
                </label>
            </div>
        </div>

        {{-- Resultado --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 md:p-6" id="result-container">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <h2 class="text-lg font-semibold text-white">Resultado</h2>

                <div class="flex items-center justify-between w-full md:w-auto gap-3">
                    <span class="text-xs md:text-sm text-gray-500 font-mono bg-neutral-900/50 py-1 px-2 rounded"
                        id="word-count">0 palavras</span>
                    <button type="button" id="copy-btn"
                        class="flex-1 md:flex-none py-2 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white active:bg-neutral-500 transition-all">
                        <i class="fa-solid fa-copy"></i>
                        Copiar
                    </button>
                </div>
            </div>

            <div class="prose prose-invert max-w-none prose-sm md:prose-base">
                <div id="text-result" class="bg-neutral-900/30 p-4 rounded-lg border border-neutral-700/30">
                    @if($type === 'paragraphs')
                        @foreach($text as $paragraph)
                            <p class="mb-4 text-gray-300 leading-relaxed last:mb-0">{{ $paragraph }}</p>
                        @endforeach
                    @elseif($type === 'sentences')
                        <p class="text-gray-300 leading-relaxed">{{ implode(' ', $text) }}</p>
                    @else
                        <p class="text-gray-300 leading-relaxed">{{ $text[0] }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Toast - Melhorado para Mobile --}}
        <div id="toast"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:translate-x-0 py-3 px-6 bg-bulma-primary text-neutral-900 rounded-full shadow-2xl font-bold transform translate-y-10 opacity-0 transition-all duration-300 pointer-events-none z-[60]">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Copiado para a área de transferência!</span>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.loremConfig = {
                generateUrl: '{{ route("tools.lorem.generate") }}',
                csrfToken: '{{ csrf_token() }}'
            };

            function updateWordCount() {
                const text = document.getElementById('text-result').textContent;
                const count = text.trim().split(/\s+/).filter(w => w.length > 0).length;
                document.getElementById('word-count').textContent = count + ' palavras';
            }

            // Inicializa contagem
            updateWordCount();
        </script>
        @vite(['resources/js/tools/lorem-generator.js'])
    @endpush
@endsection