@extends('layouts.tools')

@section('title', 'Compressor de Imagem - Online Grátis')
@section('tool_name', 'Compressor')
@section('description', 'Comprima múltiplas imagens em PNG, JPG e WebP mantendo a qualidade. Baixe tudo em ZIP.')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Compressor de Imagem</h1>
            <p class="text-gray-400">Comprima múltiplas imagens em diferentes formatos</p>
        </div>

        {{-- Upload area --}}
        <div id="upload-area"
            class="bg-neutral-800/50 border-2 border-dashed border-neutral-700/50 rounded-xl p-8 transition-colors">
            <label class="flex flex-col items-center justify-center cursor-pointer">
                <div class="w-16 h-16 rounded-full bg-neutral-700 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400"></i>
                </div>
                <p class="text-lg font-medium text-white mb-2">Arraste imagens ou clique para selecionar</p>
                <p class="text-sm text-gray-500">PNG, JPG ou WebP • Múltiplos arquivos • Máximo 20MB cada</p>
                <input type="file" id="file-input" class="hidden" accept="image/png,image/jpeg,image/webp" multiple>
            </label>
        </div>

        {{-- Global Settings --}}
        <div id="settings-container" class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-6 hidden">
            <h2 class="text-lg font-semibold text-white mb-4">Configurações Globais</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Quality --}}
                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-medium text-gray-300">Qualidade</label>
                        <span class="text-sm text-bulma-primary" id="quality-value">80%</span>
                    </div>
                    <input type="range" id="quality" value="80" min="10" max="100" step="5"
                        class="w-full h-2 bg-neutral-700 rounded-lg appearance-none cursor-pointer accent-bulma-primary">
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Menor</span>
                        <span>Maior</span>
                    </div>
                </div>

                {{-- Max width --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Largura máxima</label>
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="width-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 transition-all">
                            <span id="width-label">Original</span>
                            <svg class="hs-dropdown-open:rotate-180 size-4 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div
                            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50">
                            <button type="button" data-width-option="null"
                                class="width-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white bg-bulma-primary/10 text-bulma-primary">
                                <span>Original</span>
                            </button>
                            <button type="button" data-width-option="1920"
                                class="width-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                                <span>1920px (Full HD)</span>
                            </button>
                            <button type="button" data-width-option="1280"
                                class="width-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                                <span>1280px (HD)</span>
                            </button>
                            <button type="button" data-width-option="800"
                                class="width-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                                <span>800px (Web)</span>
                            </button>
                            <button type="button" data-width-option="640"
                                class="width-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                                <span>640px (Mobile)</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Output formats --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Formatos de saída</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="format-jpeg" value="jpeg" checked
                                class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                            <span class="text-sm text-gray-300">JPEG</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="format-webp" value="webp" checked
                                class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                            <span class="text-sm text-gray-300">WebP</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="format-png" value="png"
                                class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                            <span class="text-sm text-gray-300">PNG</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Images List --}}
        <div id="images-container" class="space-y-4 hidden">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-white">Imagens (<span id="image-count">0</span>)</h2>
                <div class="flex gap-2">
                    <button type="button" id="compress-all-btn"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 disabled:opacity-50 transition-all">
                        <i class="fa-solid fa-compress"></i>
                        Comprimir Todas
                    </button>
                    <button type="button" id="download-all-btn" disabled
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-emerald-500 text-emerald-400 hover:bg-emerald-500/10 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <i class="fa-solid fa-file-zipper"></i>
                        Download ZIP
                    </button>
                    <button type="button" id="clear-all-btn"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 text-gray-400 hover:text-white hover:bg-neutral-700 transition-all">
                        <i class="fa-solid fa-trash"></i>
                        Limpar
                    </button>
                </div>
            </div>

            <div id="images-list" class="space-y-3">
                {{-- Images will be added here dynamically --}}
            </div>
        </div>

        {{-- Features --}}
        <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 text-center">
                <i class="fa-solid fa-lock text-bulma-primary text-xl mb-2"></i>
                <h3 class="font-medium text-white mb-1">100% Privado</h3>
                <p class="text-sm text-gray-500">Processamento local no navegador</p>
            </div>
            <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 text-center">
                <i class="fa-solid fa-bolt text-bulma-primary text-xl mb-2"></i>
                <h3 class="font-medium text-white mb-1">Múltiplos Formatos</h3>
                <p class="text-sm text-gray-500">JPEG, WebP e PNG simultâneos</p>
            </div>
            <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 text-center">
                <i class="fa-solid fa-infinity text-bulma-primary text-xl mb-2"></i>
                <h3 class="font-medium text-white mb-1">Ilimitado</h3>
                <p class="text-sm text-gray-500">Sem limites de uso</p>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div id="preview-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="relative max-w-7xl w-full max-h-[90vh] bg-neutral-800 rounded-xl overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b border-neutral-700">
                <h3 class="text-lg font-semibold text-white" id="modal-title">Preview</h3>
                <button type="button" id="close-modal-btn"
                    class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-neutral-700 transition-all">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-4 overflow-auto max-h-[calc(90vh-80px)]">
                <img id="modal-image" src="" class="max-w-full h-auto mx-auto rounded-lg">
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/image-compressor.js'])
    @endpush
@endsection