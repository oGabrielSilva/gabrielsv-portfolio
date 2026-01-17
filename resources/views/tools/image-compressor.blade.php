@extends('layouts.tools')

@section('title', 'Compressor de Imagem - Online Grátis')
@section('tool_name', 'Compressor')
@section('description', 'Comprima múltiplas imagens em PNG, JPG e WebP mantendo a qualidade. Baixe tudo em ZIP.')

@section('content')
<div class="space-y-4 md:space-y-6">
    {{-- Header --}}
    <div class="px-2 md:px-0">
        <h1 class="text-xl md:text-2xl font-bold text-white mb-1 md:mb-2">Compressor de Imagem</h1>
        <p class="text-sm md:text-base text-gray-400">Comprima múltiplas imagens em diferentes formatos</p>
    </div>

    {{-- Upload area --}}
    <div id="upload-area"
        class="bg-neutral-800/50 border-2 border-dashed border-neutral-700/50 rounded-xl p-6 md:p-10 transition-colors mx-2 md:mx-0">
        <label class="flex flex-col items-center justify-center cursor-pointer">
            <div
                class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-neutral-700 flex items-center justify-center mb-3 md:mb-4">
                <i class="fa-solid fa-cloud-arrow-up text-xl md:text-2xl text-gray-400"></i>
            </div>
            <p class="text-base md:text-lg font-medium text-white mb-2 text-center">Arraste imagens ou clique para
                selecionar</p>
            <p class="text-xs md:text-sm text-gray-500 text-center uppercase tracking-wider">PNG, JPG, WebP • Máx 20MB
            </p>
            <input type="file" id="file-input" class="hidden" accept="image/png,image/jpeg,image/webp" multiple>
        </label>
    </div>

    {{-- Global Settings --}}
    <div id="settings-container"
        class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 md:p-6 hidden mx-2 md:mx-0">
        <h2 class="text-base md:text-lg font-semibold text-white mb-4">Configurações Globais</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            {{-- Quality --}}
            <div class="space-y-2">
                <div class="flex justify-between">
                    <label class="text-sm font-medium text-gray-300">Qualidade</label>
                    <span class="text-sm text-bulma-primary font-bold" id="quality-value">80%</span>
                </div>
                <input type="range" id="quality" value="80" min="10" max="100" step="5"
                    class="w-full h-2 bg-neutral-700 rounded-lg appearance-none cursor-pointer accent-bulma-primary">
                <div class="flex justify-between text-[10px] text-gray-500 uppercase">
                    <span>Menor peso</span>
                    <span>Melhor qualidade</span>
                </div>
            </div>

            {{-- Max width --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-300">Largura máxima</label>
                <div class="hs-dropdown relative w-full [--strategy:absolute] [--adaptive:none]">
                    <button id="width-dropdown" type="button"
                        class="hs-dropdown-toggle w-full py-2.5 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 transition-all">
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
                            class="width-option w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700">Original</button>
                        <button type="button" data-width-option="1920"
                            class="width-option w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700">1920px
                            (Full HD)</button>
                        <button type="button" data-width-option="1280"
                            class="width-option w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700">1280px
                            (HD)</button>
                        <button type="button" data-width-option="800"
                            class="width-option w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700">800px
                            (Web)</button>
                        <button type="button" data-width-option="640"
                            class="width-option w-full flex items-center py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700">640px
                            (Mobile)</button>
                    </div>
                </div>
            </div>

            {{-- Output formats --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-300">Formatos de saída</label>
                <div class="flex flex-wrap gap-4 sm:flex-col sm:gap-2">
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
    <div id="images-container" class="space-y-4 hidden px-2 md:px-0">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="text-lg font-semibold text-white">Imagens (<span id="image-count">0</span>)</h2>
            <div class="grid grid-cols-2 sm:flex gap-2">
                <button type="button" id="compress-all-btn"
                    class="order-1 sm:order-none py-2.5 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                    <i class="fa-solid fa-compress"></i>
                    Comprimir
                </button>
                <button type="button" id="download-all-btn" disabled
                    class="order-2 sm:order-none py-2.5 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-semibold rounded-lg border border-emerald-500 text-emerald-400 hover:bg-emerald-500/10 disabled:opacity-50 transition-all">
                    <i class="fa-solid fa-file-zipper"></i>
                    ZIP
                </button>
                <button type="button" id="clear-all-btn"
                    class="col-span-2 sm:col-auto py-2.5 px-4 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 text-gray-400 hover:text-white hover:bg-neutral-700 transition-all">
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
    <div id="features" class="grid grid-cols-1 sm:grid-cols-3 gap-3 px-2 md:px-0">
        <div
            class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 flex sm:flex-col items-center gap-4 sm:gap-2 text-left sm:text-center">
            <i class="fa-solid fa-lock text-bulma-primary text-xl"></i>
            <div>
                <h3 class="font-medium text-white text-sm">100% Privado</h3>
                <p class="text-xs text-gray-500">Processamento no navegador</p>
            </div>
        </div>
        <div
            class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 flex sm:flex-col items-center gap-4 sm:gap-2 text-left sm:text-center">
            <i class="fa-solid fa-bolt text-bulma-primary text-xl"></i>
            <div>
                <h3 class="font-medium text-white text-sm">Rápido</h3>
                <p class="text-xs text-gray-500">JPEG, WebP e PNG</p>
            </div>
        </div>
        <div
            class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 flex sm:flex-col items-center gap-4 sm:gap-2 text-left sm:text-center">
            <i class="fa-solid fa-infinity text-bulma-primary text-xl"></i>
            <div>
                <h3 class="font-medium text-white text-sm">Ilimitado</h3>
                <p class="text-xs text-gray-500">Sem limites de uso</p>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div id="preview-modal" class="hidden fixed inset-0 bg-black/90 z-[60] flex items-center justify-center p-2 md:p-4">
    <div class="relative w-full max-w-5xl bg-neutral-900 rounded-xl overflow-hidden shadow-2xl">
        <div class="flex justify-between items-center p-4 border-b border-neutral-800">
            <h3 class="text-base font-semibold text-white" id="modal-title">Prévia da Imagem</h3>
            <button type="button" id="close-modal-btn"
                class="p-2 rounded-full text-gray-400 hover:bg-neutral-800 transition-all">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-2 md:p-4 flex items-center justify-center overflow-auto max-h-[80vh]">
            <img id="modal-image" src="" class="max-w-full h-auto object-contain shadow-lg">
        </div>
    </div>
</div>