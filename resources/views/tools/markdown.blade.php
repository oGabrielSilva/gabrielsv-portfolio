@extends('layouts.tools')

@section('title', 'Markdown Preview - Editor e Previewer Online')
@section('tool_name', 'Markdown Preview')
@section('description', 'Edite e visualize Markdown em tempo real com preview renderizado')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Markdown Preview</h1>
                <p class="text-gray-400 text-sm sm:text-base">Edite e visualize Markdown em tempo real</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="copy-md-btn"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                    Markdown
                </button>
                <button type="button" id="copy-html-btn"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="code" class="w-4 h-4"></i>
                    HTML
                </button>
                <button type="button" id="example-btn"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Exemplo
                </button>
            </div>
        </div>

        {{-- Editor + Preview --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Editor --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl overflow-hidden">
                <div class="px-4 py-2 border-b border-neutral-700/50 flex items-center justify-between">
                    <span class="text-sm text-gray-400 font-medium">Editor</span>
                    <span class="text-xs text-gray-500" id="char-count">0 caracteres</span>
                </div>
                <textarea id="md-input"
                    class="w-full h-[500px] p-4 bg-transparent text-gray-200 font-mono text-sm resize-none focus:outline-none placeholder-gray-600"
                    placeholder="Digite seu Markdown aqui..."
                    spellcheck="false"></textarea>
            </div>

            {{-- Preview --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl overflow-hidden">
                <div class="px-4 py-2 border-b border-neutral-700/50">
                    <span class="text-sm text-gray-400 font-medium">Preview</span>
                </div>
                <div id="md-preview"
                    class="p-4 h-[500px] overflow-y-auto prose prose-invert prose-sm max-w-none prose-headings:text-white prose-p:text-gray-300 prose-a:text-bulma-primary prose-strong:text-white prose-code:text-bulma-primary prose-code:bg-neutral-700 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-pre:bg-neutral-900 prose-pre:border prose-pre:border-neutral-700 prose-blockquote:border-bulma-primary prose-blockquote:text-gray-400 prose-th:text-gray-200 prose-td:text-gray-300">
                    <p class="text-gray-500 italic">O preview aparecerá aqui...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
        class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2 z-50">
        <i data-lucide="check" class="w-4 h-4"></i>
        <span id="toast-text">Copiado!</span>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/markdown.js'])
    @endpush
@endsection
