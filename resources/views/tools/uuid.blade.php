@extends('layouts.tools')

@section('title', $typeInfo['label'] . ' Generator - Gerador Online')
@section('tool_name', $typeInfo['label'] . ' Generator')
@section('description', 'Gere ' . $typeInfo['label'] . ' online gratuitamente. ' . $typeInfo['description'])

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">{{ $typeInfo['label'] }} Generator</h1>
            <p class="text-gray-400 text-sm sm:text-base">{{ $typeInfo['description'] }}</p>
        </div>

        {{-- Configurações --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tipo de UUID --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="uuid-type-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                            <span>{{ $typeInfo['label'] }}</span>
                            <i data-lucide="chevron-down"
                                class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform"></i>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                            role="menu" aria-orientation="vertical">
                            @foreach($types as $typeKey => $typeData)
                                <a href="{{ route('tools.uuid.type', ['type' => $typeKey]) }}"
                                    class="flex items-center justify-between gap-x-3.5 py-2 px-3 rounded-lg text-sm transition-colors
                                                                                                            {{ $currentType === $typeKey ? 'bg-bulma-primary/10 text-bulma-primary' : 'text-gray-300 hover:bg-neutral-700 hover:text-white' }}">
                                    <span>{{ $typeData['label'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $typeData['short'] }}</span>
                                </a>
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
                <div class="flex items-end col-span-full">
                    <button type="button" id="generate-btn"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <i data-lucide="sparkles" class="w-4 h-4" id="generate-icon"></i>
                        <span id="generate-text">Gerar</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6" id="results-container">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-lg font-semibold text-white">Resultados</h2>
                <button type="button" id="copy-all-btn"
                    class="py-2 px-3 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                    Copiar todos
                </button>
            </div>

            <div class="space-y-2" id="ids-list">
                @foreach($ids as $id)
                    <div class="flex items-center gap-2 group">
                        <code
                            class="flex-1 py-2 px-3 bg-neutral-900 rounded-lg text-xs sm:text-sm text-gray-300 font-mono break-all uuid-item">{{ $id }}</code>
                        <button type="button"
                            class="copy-btn p-2 text-gray-500 hover:text-bulma-primary transition-colors sm:opacity-0 sm:group-hover:opacity-100"
                            data-value="{{ $id }}" title="Copiar">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Info sobre tipos --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Sobre os tipos de UUID</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                @foreach($types as $typeKey => $typeData)
                    <div>
                        <h3 class="font-medium text-bulma-primary mb-1">{{ $typeData['label'] }}</h3>
                        <p class="text-gray-400">{{ $typeData['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Toast --}}
        <div id="toast"
            class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2">
            <i data-lucide="check" class="w-4 h-4"></i>
            Copiado!
        </div>
    </div>

    @push('scripts')
        <script>
            window.uuidConfig = {
                generateUrl: '{{ route('tools.uuid.generate') }}',
                csrfToken: '{{ csrf_token() }}',
                currentType: '{{ $currentType }}'
            };
        </script>
        @vite(['resources/js/tools/uuid-generator.js'])
    @endpush
@endsection
