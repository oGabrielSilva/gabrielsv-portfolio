@extends('layouts.tools')

@section('title', 'Lorem Ipsum Generator - Gerador Online')
@section('tool_name', 'Lorem Ipsum')
@section('description', 'Gere textos Lorem Ipsum para seus projetos de design e desenvolvimento')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Lorem Ipsum Generator</h1>
            <p class="text-gray-400">Gere textos placeholder para seus projetos</p>
        </div>

        {{-- Configurações --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="lorem-type-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            <span id="lorem-type-label">{{ $types[$type]['label'] }}</span>
                            <svg class="hs-dropdown-open:rotate-180 size-4 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                            role="menu" aria-orientation="vertical">
                            @foreach($types as $typeKey => $typeData)
                                <button type="button" data-lorem-type="{{ $typeKey }}"
                                    class="w-full flex items-center justify-between gap-x-3.5 py-2 px-3 rounded-lg text-sm transition-colors text-gray-300 hover:bg-neutral-700 hover:text-white {{ $type === $typeKey ? 'bg-bulma-primary/10 text-bulma-primary' : '' }}">
                                    <span>{{ $typeData['label'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $typeData['description'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Quantidade --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-300 mb-2">Quantidade</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $quantity }}" min="1" max="50"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all">
                </div>

                {{-- Botão Gerar --}}
                <div class="flex items-end">
                    <button type="button" id="generate-btn"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles" id="generate-icon"></i>
                        <span id="generate-text">Gerar</span>
                    </button>
                </div>
            </div>

            {{-- Opções --}}
            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-neutral-700/50">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="start-with-lorem" {{ $startWithLorem ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                    <span class="text-sm text-gray-300">Começar com "Lorem ipsum..."</span>
                </label>
            </div>
        </div>

        {{-- Resultado --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6" id="result-container">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-white">Resultado</h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500" id="word-count">0 palavras</span>
                    <button type="button" id="copy-btn"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i class="fa-solid fa-copy"></i>
                        Copiar
                    </button>
                </div>
            </div>
            <div class="prose prose-invert max-w-none">
                <div id="text-result">
                    @if($type === 'paragraphs')
                        @foreach($text as $paragraph)
                            <p class="mb-4 text-gray-300 leading-relaxed">{{ $paragraph }}</p>
                        @endforeach
                    @elseif($type === 'sentences')
                        <p class="text-gray-300 leading-relaxed">{{ implode(' ', $text) }}</p>
                    @else
                        <p class="text-gray-300 leading-relaxed">{{ $text[0] }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Toast --}}
        <div id="toast"
            class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none">
            <i class="fa-solid fa-check mr-2"></i>
            Copiado!
        </div>
    </div>

    @push('scripts')
        <script>
            window.loremConfig = {
                generateUrl: '{{ route("tools.lorem.generate") }}',
                csrfToken: '{{ csrf_token() }}'
            };

            // Atualiza contagem de palavras inicial
            const initialText = document.getElementById('text-result').textContent;
            const wordCount = initialText.split(/\s+/).filter(w => w.trim()).length;
            document.getElementById('word-count').textContent = wordCount + ' palavras';
        </script>
        @vite(['resources/js/tools/lorem-generator.js'])
    @endpush
@endsection