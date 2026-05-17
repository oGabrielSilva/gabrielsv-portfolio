@extends('layouts.tools')

@section('title', 'Gerador de Link de WhatsApp (wa.me) com Mensagem Pré-preenchida')
@section('tool_name', 'Link WhatsApp')
@section('description', 'Cria link wa.me com número, DDI e mensagem pronta. Para botão de contato no site, link da bio do Instagram e campanha de marketing.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="whatsapp-link">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador de Link de WhatsApp</h1>
            <p class="text-gray-400 text-sm sm:text-base">
                Para botão de contato no site, link da bio e campanha. Coloca número e mensagem, o link <code class="text-bulma-primary">wa.me</code> aparece pronto.
            </p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-3">
                <div>
                    <label for="wa-ddi" class="block text-sm font-medium text-gray-300 mb-2">DDI (país)</label>
                    <input type="text" id="wa-ddi" value="55" inputmode="numeric"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono">
                </div>
                <div>
                    <label for="wa-phone" class="block text-sm font-medium text-gray-300 mb-2">Número (com DDD)</label>
                    <input type="text" id="wa-phone" placeholder="11 91234-5678" inputmode="tel"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all">
                </div>
            </div>

            <div>
                <label for="wa-message" class="block text-sm font-medium text-gray-300 mb-2">
                    Mensagem (opcional)
                    <span class="text-gray-500 font-normal">— quebras de linha são preservadas</span>
                </label>
                <textarea id="wa-message" rows="4" placeholder="Olá! Vi seu site e gostaria de saber mais sobre..."
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm"></textarea>
            </div>

            <fieldset>
                <legend class="block text-sm font-medium text-gray-300 mb-2">Formato do link</legend>
                <div class="flex flex-wrap gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="wa-format" value="wa.me" checked
                            class="w-4 h-4 border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                        <span class="text-sm text-gray-300">wa.me (curto, padrão)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="wa-format" value="api"
                            class="w-4 h-4 border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                        <span class="text-sm text-gray-300">api.whatsapp.com (compat. legada)</span>
                    </label>
                </div>
            </fieldset>

            <div id="wa-status" class="hidden py-2 px-3 rounded-lg text-sm font-medium"></div>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-white">Link gerado</h2>
                <div class="flex items-center gap-2">
                    <a id="wa-open" target="_blank" rel="noopener noreferrer"
                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all opacity-50 pointer-events-none">
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        Abrir
                    </a>
                    <button type="button" id="wa-copy"
                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="copy" class="w-3 h-3"></i>
                        Copiar
                    </button>
                </div>
            </div>
            <code id="wa-output" class="block py-3 px-4 bg-neutral-900 rounded-lg text-sm text-gray-300 font-mono break-all">—</code>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">HTML pronto</label>
                <code id="wa-html" class="block py-3 px-4 bg-neutral-900 rounded-lg text-sm text-gray-300 font-mono break-all">—</code>
            </div>
        </div>

        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6 text-sm text-gray-400 space-y-2">
            <p>
                <strong class="text-bulma-primary">Dica:</strong> o número deve estar em formato internacional,
                sem <code>+</code>, sem espaços e sem o <code>0</code> de operadora.
                Ex.: <code>5511912345678</code> (Brasil, São Paulo).
            </p>
            <p>
                A mensagem pré-preenchida abre o WhatsApp com o texto digitado, mas o envio
                continua dependendo de o usuário tocar em "Enviar".
            </p>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/whatsapp-link.js'])
    @endpush
@endsection
