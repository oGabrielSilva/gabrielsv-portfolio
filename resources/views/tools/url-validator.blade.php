@extends('layouts.tools')

@section('title', 'Validador e Analisador de URL Online')
@section('tool_name', 'Validador de URL')
@section('description', 'Valide URLs e decomponha em seus componentes: protocolo, host, path, query string e fragmento. Detecta problemas comuns.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="url-validator">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Validador de URL</h1>
            <p class="text-gray-400 text-sm sm:text-base">Veja se uma URL é válida e qual o significado de cada parte.</p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <label for="url-input" class="block text-sm font-medium text-gray-300 mb-2">URL</label>
            <input type="text" id="url-input" placeholder="https://exemplo.com/path?query=1"
                value="https://gabrielsv.com/tools/url-validator?utm=demo#start"
                class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono text-sm">
            <div id="status" class="mt-3 hidden py-2 px-3 rounded-lg text-sm font-medium"></div>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Componentes</h2>
            <dl class="space-y-3 text-sm" id="parts-list">
                @php
                    $parts = [
                        'protocol' => 'Protocolo',
                        'host' => 'Host',
                        'hostname' => 'Hostname',
                        'port' => 'Porta',
                        'pathname' => 'Path',
                        'search' => 'Query string',
                        'hash' => 'Fragmento',
                        'username' => 'Usuário',
                        'password' => 'Senha',
                    ];
                @endphp
                @foreach($parts as $key => $label)
                    <div class="flex gap-3" data-part="{{ $key }}">
                        <dt class="w-32 shrink-0 text-bulma-primary font-medium">{{ $label }}</dt>
                        <dd class="flex-1 font-mono text-gray-300 break-all">—</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Query parameters</h2>
            <div id="query-list" class="space-y-2 text-sm text-gray-400">Nenhum parâmetro.</div>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Diagnóstico</h2>
            <ul id="warnings" class="space-y-2 text-sm text-gray-400"></ul>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/url-validator.js'])
    @endpush
@endsection
