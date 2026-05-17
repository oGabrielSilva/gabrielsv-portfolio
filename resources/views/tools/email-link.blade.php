@extends('layouts.tools')

@section('title', 'Gerador de Link mailto: com CC, BCC, Assunto e Corpo')
@section('tool_name', 'Link de E-mail')
@section('description', 'Monta link mailto: que abre o cliente de e-mail já com destinatário, CC, BCC, assunto e corpo preenchidos. Para botão de contato, assinatura e suporte.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="email-link">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador de Link de E-mail</h1>
            <p class="text-gray-400 text-sm sm:text-base">
                Link <code class="text-bulma-primary">mailto:</code> com CC, BCC, assunto e corpo prontos. Para botão de contato, assinatura e suporte.
            </p>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-4">
            <div>
                <label for="em-to" class="block text-sm font-medium text-gray-300 mb-2">
                    Para <span class="text-gray-500 font-normal">— separe vários por vírgula</span>
                </label>
                <input type="text" id="em-to" placeholder="contato@exemplo.com, vendas@exemplo.com" inputmode="email"
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="em-cc" class="block text-sm font-medium text-gray-300 mb-2">CC</label>
                    <input type="text" id="em-cc" placeholder="cc@exemplo.com" inputmode="email"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono text-sm">
                </div>
                <div>
                    <label for="em-bcc" class="block text-sm font-medium text-gray-300 mb-2">BCC</label>
                    <input type="text" id="em-bcc" placeholder="bcc@exemplo.com" inputmode="email"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all font-mono text-sm">
                </div>
            </div>

            <div>
                <label for="em-subject" class="block text-sm font-medium text-gray-300 mb-2">Assunto</label>
                <input type="text" id="em-subject" placeholder="Sobre o seu serviço"
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
            </div>

            <div>
                <label for="em-body" class="block text-sm font-medium text-gray-300 mb-2">
                    Corpo <span class="text-gray-500 font-normal">— quebras de linha preservadas</span>
                </label>
                <textarea id="em-body" rows="5" placeholder="Olá, gostaria de mais informações sobre..."
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm"></textarea>
            </div>

            <div id="em-status" class="hidden py-2 px-3 rounded-lg text-sm font-medium"></div>
        </div>

        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-white">Link gerado</h2>
                <div class="flex items-center gap-2">
                    <a id="em-open"
                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all opacity-50 pointer-events-none">
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                        Abrir
                    </a>
                    <button type="button" id="em-copy"
                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="copy" class="w-3 h-3"></i>
                        Copiar
                    </button>
                </div>
            </div>
            <code id="em-output" class="block py-3 px-4 bg-neutral-900 rounded-lg text-sm text-gray-300 font-mono break-all">—</code>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">HTML pronto</label>
                <code id="em-html" class="block py-3 px-4 bg-neutral-900 rounded-lg text-sm text-gray-300 font-mono break-all">—</code>
            </div>
        </div>

        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6 text-sm text-gray-400 space-y-2">
            <p>
                <strong class="text-bulma-primary">Como funciona:</strong> ao clicar, o link abre o cliente
                de e-mail padrão do dispositivo (Gmail, Outlook, Apple Mail) já com os campos preenchidos.
                O envio sempre exige confirmação humana — o link só prepara o rascunho.
            </p>
            <p>
                Use o campo <strong>BCC</strong> para enviar a destinatários que não devem ver os outros endereços.
            </p>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/email-link.js'])
    @endpush
@endsection
