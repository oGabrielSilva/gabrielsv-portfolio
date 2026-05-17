@extends('layouts.tools')

@section('title', 'Horário Mundial: que horas são em São Paulo, Lisboa, NY e Tóquio')
@section('tool_name', 'Horário Mundial')
@section('description', 'Para quem trabalha com time remoto ou agenda call com cliente fora do Brasil. Horários ao vivo nos principais fusos do mundo.')

@section('content')
    <div class="space-y-4 sm:space-y-6"
        data-tool="world-clock"
        data-search-url="{{ route('tools.world-clock.search') }}">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Horário Mundial</h1>
            <p class="text-gray-400 text-sm sm:text-base">Para quem trabalha com time remoto ou agenda call com cliente fora do Brasil.</p>
        </div>

        {{-- Relógio local --}}
        <div class="bg-neutral-800/50 border border-bulma-primary/30 rounded-xl p-4 sm:p-6">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-bulma-primary"></i>
                <span class="text-sm font-medium text-bulma-primary">Seu horário local</span>
            </div>
            <div class="flex items-end gap-3">
                <span id="local-time" class="text-3xl sm:text-4xl font-bold text-white font-mono">--:--:--</span>
                <span id="local-date" class="text-gray-400 text-sm mb-1"></span>
            </div>
            <span id="local-timezone" class="text-gray-500 text-xs mt-1 block"></span>
        </div>

        {{-- Buscar cidade --}}
        <div class="relative">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
                    <input type="text" id="city-search"
                        class="w-full py-3 pl-10 pr-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all"
                        placeholder="Buscar cidade..." autocomplete="off">
                </div>
            </div>
            <div id="search-results" class="absolute z-50 w-full mt-1 bg-neutral-800 border border-neutral-700 rounded-lg shadow-xl hidden max-h-60 overflow-y-auto"></div>
        </div>

        {{-- Grid de relógios --}}
        <div id="clocks-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/world-clock.js'])
    @endpush
@endsection
