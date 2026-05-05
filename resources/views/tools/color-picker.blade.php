@extends('layouts.tools')

@section('title', 'Seletor de Cores - Color Picker Online')
@section('tool_name', 'Seletor de Cores')
@section('description', 'Converta cores entre HEX, RGB e HSL. Gere paletas complementares, análogas e triádicas.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Seletor de Cores</h1>
            <p class="text-gray-400 text-sm sm:text-base">Converta cores e gere paletas harmônicas</p>
        </div>

        {{-- Color preview + picker --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="relative">
                    <div id="color-preview" class="w-32 h-32 rounded-xl border-2 border-neutral-600 cursor-pointer transition-all" style="background: #00d1b2;"></div>
                    <input type="color" id="color-picker" value="#00d1b2"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
                    {{-- HEX --}}
                    <div>
                        <label for="hex-input" class="block text-xs text-gray-500 uppercase tracking-wide mb-1">HEX</label>
                        <div class="flex gap-2">
                            <input type="text" id="hex-input" value="#00d1b2"
                                class="flex-1 py-2 px-3 font-mono text-sm rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary"
                                maxlength="7">
                            <button class="copy-val py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-gray-400 hover:text-white transition-all" data-target="hex-input">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                    {{-- RGB --}}
                    <div>
                        <label for="rgb-input" class="block text-xs text-gray-500 uppercase tracking-wide mb-1">RGB</label>
                        <div class="flex gap-2">
                            <input type="text" id="rgb-input" value="rgb(0, 209, 178)"
                                class="flex-1 py-2 px-3 font-mono text-sm rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                            <button class="copy-val py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-gray-400 hover:text-white transition-all" data-target="rgb-input">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                    {{-- HSL --}}
                    <div>
                        <label for="hsl-input" class="block text-xs text-gray-500 uppercase tracking-wide mb-1">HSL</label>
                        <div class="flex gap-2">
                            <input type="text" id="hsl-input" value="hsl(171, 100%, 41%)"
                                class="flex-1 py-2 px-3 font-mono text-sm rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                            <button class="copy-val py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-gray-400 hover:text-white transition-all" data-target="hsl-input">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                    {{-- HSB --}}
                    <div>
                        <label for="hsb-input" class="block text-xs text-gray-500 uppercase tracking-wide mb-1">HSB</label>
                        <div class="flex gap-2">
                            <input type="text" id="hsb-input" value="hsb(171, 100%, 82%)"
                                class="flex-1 py-2 px-3 font-mono text-sm rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary">
                            <button class="copy-val py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-gray-400 hover:text-white transition-all" data-target="hsb-input">
                                <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Paletas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Complementar --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Complementar</h3>
                <div id="palette-complementary" class="flex gap-2 h-14"></div>
            </div>
            {{-- Análoga --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Análoga</h3>
                <div id="palette-analogous" class="flex gap-2 h-14"></div>
            </div>
            {{-- Triádica --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Triádica</h3>
                <div id="palette-triadic" class="flex gap-2 h-14"></div>
            </div>
            {{-- Tetrádica --}}
            <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Tetrádica</h3>
                <div id="palette-tetradic" class="flex gap-2 h-14"></div>
            </div>
        </div>

        {{-- Tints & Shades --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Tons (Tints & Shades)</h3>
            <div id="tints-shades" class="flex gap-1 h-16 rounded-lg overflow-hidden"></div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast"
        class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2 z-50">
        <i data-lucide="check" class="w-4 h-4"></i>
        Copiado!
    </div>

    @push('scripts')
        @vite(['resources/js/tools/color-picker.js'])
    @endpush
@endsection
