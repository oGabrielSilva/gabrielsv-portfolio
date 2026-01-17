@extends('layouts.tools')

@section('title', $typeInfo['label'] . ' Generator - Gerador Online')
@section('tool_name', $typeInfo['label'] . ' Generator')
@section('description', 'Gere ' . $typeInfo['label'] . ' online gratuitamente. ' . $typeInfo['description'])

@section('content')
    <div class="space-y-4 md:space-y-6 px-2 md:px-0">
        {{-- Header --}}
        <div class="px-2 md:px-0">
            <h1 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">{{ $typeInfo['label'] }} Generator</h1>
            <p class="text-sm md:text-base text-gray-400">{{ $typeInfo['description'] }}</p>
        </div>

        {{-- Configurações --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {{-- Tipo de UUID --}}
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
                    <div class="hs-dropdown relative w-full [--strategy:absolute] [--adaptive:none]">
                        <button id="uuid-type-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 transition-all">
                            <span>{{ $typeInfo['label'] }}</span>
                            <svg class="hs-dropdown-open:rotate-180 size-4 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div
                            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50">
                            @foreach($types as $typeKey => $typeData)
                                <a href="{{ route('tools.uuid.type', ['type' => $typeKey]) }}"
                                    class="flex items-center justify-between gap-x-3.5 py-2.5 px-3 rounded-lg text-sm transition-colors
                                            {{ $currentType === $typeKey ? 'bg-bulma-primary/10 text-bulma-primary' : 'text-gray-300 hover:bg-neutral-700 hover:text-white' }}">
                                    <span class="font-medium">{{ $typeData['label'] }}</span>
                                    <span
                                        class="text-[10px] opacity-50 uppercase tracking-tighter">{{ $typeData['short'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Quantidade --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-300 mb-2">Quantidade</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $quantity }}" min="1" max="50"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:ring-2 focus:ring-bulma-primary outline-none transition-all">
                </div>

                {{-- Botão Gerar --}}
                <div class="flex items-end sm:col-span-2 md:col-span-1">
                    <button type="button" id="generate-btn"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 active:scale-[0.98] transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles" id="generate-icon"></i>
                        <span id="generate-text">Gerar IDs</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 md:p-6" id="results-container">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                <h2 class="text-lg font-semibold text-white">Resultados</h2>
                <button type="button" id="copy-all-btn"
                    class="w-full sm:w-auto py-2 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i class="fa-solid fa-copy"></i>
                    Copiar todos
                </button>
            </div>

            <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1 custom-scrollbar" id="ids-list">
                @foreach($ids as $id)
                    <div class="flex items-center gap-2 group">
                        <code
                            class="flex-1 py-2.5 px-3 bg-neutral-900 border border-neutral-700/30 rounded-lg text-xs md:text-sm text-bulma-primary/90 font-mono break-all uuid-item">{{ $id }}</code>
                        <button type="button"
                            class="copy-btn p-2.5 text-gray-500 hover:text-bulma-primary active:text-bulma-primary md:opacity-0 md:group-hover:opacity-100 transition-all transition-opacity"
                            data-value="{{ $id }}">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Info sobre tipos --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 md:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Sobre os tipos de UUID</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8">
                @foreach($types as $typeKey => $typeData)
                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-bulma-primary uppercase tracking-wider">{{ $typeData['label'] }}</h3>
                        <p class="text-xs md:text-sm text-gray-400 leading-relaxed">{{ $typeData['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Toast - Centralizado no Mobile --}}
        <div id="toast"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:translate-x-0 py-3 px-6 bg-bulma-primary text-neutral-900 rounded-full shadow-2xl font-bold transform translate-y-10 opacity-0 transition-all duration-300 pointer-events-none z-[60]">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Copiado!</span>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.uuidConfig = {
                generateUrl: '{{ route("tools.uuid.generate") }}',
                csrfToken: '{{ csrf_token() }}',
                currentType: '{{ $currentType }}'
            };
        </script>
        @vite(['resources/js/tools/uuid-generator.js'])
    @endpush
@endsection