@extends('layouts.tools')

@php
    $isCpf = $type === 'cpf';
    $label = $isCpf ? 'CPF' : 'CNPJ';
@endphp

@section('title', "Gerador e Validador de {$label} Online")
@section('tool_name', $label)
@section('description', "Gere e valide {$label} online gratuitamente. Ferramenta para desenvolvedores e testes.")

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador e Validador de CPF/CNPJ</h1>
            <p class="text-gray-400 text-sm sm:text-base">Gere documentos válidos para testes ou valide números existentes
            </p>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-neutral-700">
            <nav class="flex gap-x-1" aria-label="Tabs" role="tablist">
                <button type="button"
                    class="tab-btn py-3 px-4 text-sm font-medium border-b-2 transition-colors {{ $isCpf ? 'border-bulma-primary text-bulma-primary' : 'border-transparent text-gray-400 hover:text-gray-300' }}"
                    data-tab="cpf" role="tab">
                    CPF
                </button>
                <button type="button"
                    class="tab-btn py-3 px-4 text-sm font-medium border-b-2 transition-colors {{ !$isCpf ? 'border-bulma-primary text-bulma-primary' : 'border-transparent text-gray-400 hover:text-gray-300' }}"
                    data-tab="cnpj" role="tab">
                    CNPJ
                </button>
            </nav>
        </div>

        {{-- Gerador --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Gerar <span id="type-label">CPF</span></h2>

            <div class="grid grid-cols-1 gap-4">
                {{-- Quantidade --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-300 mb-2">Quantidade</label>
                    <input type="number" id="quantity" value="5" min="1" max="50"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all">
                </div>

                {{-- Formatação --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Formatação</label>
                    <div class="flex items-center gap-4 h-[50px]">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="formatted" checked
                                class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-neutral-800">
                            <span class="ml-2 text-sm text-gray-300">Com pontuação</span>
                        </label>
                    </div>
                </div>

                {{-- Botão Gerar --}}
                <div class="flex items-end col-span-full">
                    <button type="button" id="generate-btn"
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>Gerar</span>
                    </button>
                </div>
            </div>

            {{-- Resultados --}}
            <div class="mt-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                    <h3 class="text-sm font-medium text-gray-300">Resultados</h3>
                    <button type="button" id="copy-all-btn"
                        class="py-2 px-3 inline-flex items-center justify-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        Copiar todos
                    </button>
                </div>

                <div class="space-y-2" id="results-list">
                    {{-- Resultados serão inseridos aqui via JS --}}
                </div>
            </div>
        </div>

        {{-- Validador --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Validar <span id="validate-type-label">CPF</span></h2>

            <div class="space-y-4">
                <div>
                    <label for="validate-input" class="block text-sm font-medium text-gray-300 mb-2">
                        Digite o <span id="input-type-label">CPF</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="validate-input" placeholder="000.000.000-00"
                            class="flex-1 py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono">
                        <button type="button" id="validate-btn"
                            class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-neutral-700 border border-neutral-600 text-white hover:bg-neutral-600 transition-all">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Validar
                        </button>
                    </div>
                </div>

                {{-- Resultado da validação --}}
                <div id="validation-result" class="hidden">
                    <div id="validation-message" class="py-3 px-4 rounded-lg flex items-center gap-2 text-sm font-medium">
                        {{-- Mensagem inserida via JS --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Sobre CPF e CNPJ</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <h3 class="font-medium text-bulma-primary mb-1">CPF</h3>
                    <p class="text-gray-400">Cadastro de Pessoas Fisicas. Documento com 11 digitos usado para identificar
                        cidadaos brasileiros.</p>
                </div>
                <div>
                    <h3 class="font-medium text-bulma-primary mb-1">CNPJ</h3>
                    <p class="text-gray-400">Cadastro Nacional da Pessoa Juridica. Documento com 14 digitos que identifica
                        empresas no Brasil.</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">
                Os documentos gerados sao matematicamente validos, porem ficticios. Use apenas para testes e
                desenvolvimento.
            </p>
        </div>

        {{-- Toast --}}
        <div id="toast"
            class="fixed bottom-4 right-4 py-3 px-4 bg-bulma-primary text-neutral-900 rounded-lg shadow-lg font-medium transform translate-y-2 opacity-0 transition-all duration-300 pointer-events-none inline-flex items-center gap-2 z-50">
            <i data-lucide="check" class="w-4 h-4"></i>
            <span id="toast-message">Copiado!</span>
        </div>
    </div>

    @push('scripts')
        <script>
            window.cpfCnpjConfig = {
                type: '{{ $type }}',
                urls: {
                    cpf: '{{ route('tools.cpf') }}',
                    cnpj: '{{ route('tools.cnpj') }}'
                }
            };
        </script>
        @vite(['resources/js/tools/cpf-cnpj.js'])
    @endpush
@endsection