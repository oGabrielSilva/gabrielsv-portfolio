@extends('layouts.tools')

@section('title', 'Conversor de Unidades CSS (px, rem, em, %)')
@section('tool_name', 'Conversor de Unidades')
@section('description', 'Converta entre px, rem, em, %, pt e cm em CSS. Tamanho base configurável para projetos com root font-size customizado.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="unit-converter">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Conversor de Unidades CSS</h1>
            <p class="text-gray-400 text-sm sm:text-base">Digite o valor em qualquer unidade — as outras se atualizam ao vivo.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="base-size" class="block text-sm font-medium text-gray-300 mb-2">Tamanho base (root font-size)</label>
            <div class="flex items-center gap-3">
                <input type="number" id="base-size" value="16" min="1" max="100" step="1"
                    class="w-24 py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all font-mono">
                <span class="text-sm text-gray-500">px (padrão dos browsers é 16px)</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="unit-grid">
            @php
                $units = [
                    'px' => 'Pixels',
                    'rem' => 'REM',
                    'em' => 'EM',
                    'percent' => 'Porcentagem',
                    'pt' => 'Pontos',
                    'cm' => 'Centímetros',
                ];
            @endphp
            @foreach($units as $key => $label)
                <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4">
                    <label class="block text-xs font-medium text-bulma-primary uppercase tracking-wider mb-2">{{ $label }} ({{ $key === 'percent' ? '%' : $key }})</label>
                    <input type="number" data-unit="{{ $key }}" step="any"
                        class="unit-input w-full py-2 px-3 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all font-mono">
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/unit-converter.js'])
    @endpush
@endsection
