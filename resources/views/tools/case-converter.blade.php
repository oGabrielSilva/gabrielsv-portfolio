@extends('layouts.tools')

@section('title', 'Conversor de Case Online (camelCase, snake_case, kebab)')
@section('tool_name', 'Conversor de Case')
@section('description', 'Converta texto entre UPPER, lower, Title, Sentence, camelCase, PascalCase, snake_case, kebab-case e CONSTANT_CASE.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="case-converter">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Conversor de Case</h1>
            <p class="text-gray-400 text-sm sm:text-base">Veja seu texto convertido em todas as variantes ao mesmo tempo.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="case-input" class="block text-sm font-medium text-gray-300 mb-2">Texto original</label>
            <textarea id="case-input" rows="4" placeholder="Digite ou cole seu texto..."
                class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">Olá mundo, como vai você?</textarea>
        </div>

        <div class="space-y-3" id="case-outputs">
            @php
                $variants = [
                    'upper' => 'UPPERCASE',
                    'lower' => 'lowercase',
                    'title' => 'Title Case',
                    'sentence' => 'Sentence case',
                    'camel' => 'camelCase',
                    'pascal' => 'PascalCase',
                    'snake' => 'snake_case',
                    'kebab' => 'kebab-case',
                    'constant' => 'CONSTANT_CASE',
                ];
            @endphp
            @foreach($variants as $key => $label)
                <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 flex items-center gap-3" data-variant="{{ $key }}">
                    <span class="text-xs font-medium text-bulma-primary w-32 shrink-0">{{ $label }}</span>
                    <code class="case-output flex-1 font-mono text-sm text-gray-300 break-all"></code>
                    <button type="button" class="copy-variant py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="copy" class="w-3 h-3"></i>
                        Copiar
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/case-converter.js'])
    @endpush
@endsection
