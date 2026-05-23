@extends('layouts.tools')

@section('title', 'Gerador de Arquivo ICS - Criar Evento de Calendário Online')
@section('tool_name', 'Gerador de ICS')
@section('description', 'Cria arquivo .ics pronto para Google Calendar, Apple, Outlook e Yahoo. Com lembrete, recorrência, fuso BR e link compartilhável. Tudo no navegador.')

@section('content')
    <div class="space-y-4 sm:space-y-6" data-tool="ics-generator">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">Gerador de ICS</h1>
            <p class="text-gray-400 text-sm sm:text-base">
                Cria um evento de calendário pronto para Google, Apple, Outlook e Yahoo. Lembrete, recorrência e fuso BR já preenchidos. Tudo no navegador.
            </p>
        </div>

        {{-- Card 1: Form --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-4">
            <div>
                <label for="ics-title" class="block text-sm font-medium text-gray-300 mb-2">
                    Título <span class="text-red-400">*</span>
                </label>
                <input type="text" id="ics-title" maxlength="200" placeholder="Reunião de planejamento"
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
            </div>

            <div>
                <label for="ics-description" class="block text-sm font-medium text-gray-300 mb-2">
                    Descrição
                </label>
                <textarea id="ics-description" rows="3" maxlength="2000" placeholder="Pauta, links, anotações..."
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ics-location" class="block text-sm font-medium text-gray-300 mb-2">Local</label>
                    <input type="text" id="ics-location" maxlength="200" placeholder="Sala 3, ou link da call"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
                </div>
                <div>
                    <label for="ics-url" class="block text-sm font-medium text-gray-300 mb-2">URL do evento</label>
                    <input type="url" id="ics-url" placeholder="https://..."
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm font-mono">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="ics-all-day"
                    class="w-4 h-4 rounded border-neutral-600 bg-neutral-700 text-bulma-primary focus:ring-bulma-primary focus:ring-offset-0">
                <label for="ics-all-day" class="text-sm text-gray-300 cursor-pointer">Dia todo</label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="ics-start" class="block text-sm font-medium text-gray-300 mb-2">
                        Início <span class="text-red-400">*</span>
                    </label>
                    <input type="datetime-local" id="ics-start"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
                </div>
                <div>
                    <label for="ics-end" class="block text-sm font-medium text-gray-300 mb-2">
                        Fim <span class="text-red-400">*</span>
                    </label>
                    <input type="datetime-local" id="ics-end"
                        class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
                </div>
            </div>

            @php
                $timezoneGroups = [
                    'Brasil' => [
                        'America/Sao_Paulo' => 'São Paulo (UTC-3)',
                        'America/Manaus' => 'Manaus (UTC-4)',
                        'America/Recife' => 'Recife (UTC-3)',
                        'America/Noronha' => 'Fernando de Noronha (UTC-2)',
                        'America/Rio_Branco' => 'Rio Branco (UTC-5)',
                    ],
                    'Outros' => [
                        'UTC' => 'UTC',
                        'Europe/Lisbon' => 'Lisboa',
                        'Europe/London' => 'Londres',
                        'America/New_York' => 'Nova York',
                        'America/Los_Angeles' => 'Los Angeles',
                        'Asia/Tokyo' => 'Tóquio',
                    ],
                ];
                $reminderOptions = [
                    '' => 'Nenhum',
                    '5' => '5 minutos antes',
                    '10' => '10 minutos antes',
                    '15' => '15 minutos antes',
                    '30' => '30 minutos antes',
                    '60' => '1 hora antes',
                    '1440' => '1 dia antes',
                ];
                $recurrenceOptions = [
                    '' => 'Não repete',
                    'daily' => 'Diário',
                    'weekly' => 'Semanal',
                    'monthly' => 'Mensal',
                    'yearly' => 'Anual',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Fuso horário --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fuso horário</label>
                    <input type="hidden" id="ics-timezone" value="America/Sao_Paulo">
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="ics-timezone-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Selecionar fuso horário">
                            <span id="ics-timezone-label" class="truncate">São Paulo (UTC-3)</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform shrink-0"></i>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50 max-h-72 overflow-y-auto"
                            role="menu" aria-orientation="vertical">
                            @foreach($timezoneGroups as $groupLabel => $items)
                                <p class="px-3 py-1.5 text-[10px] uppercase tracking-wider text-gray-500 font-semibold">{{ $groupLabel }}</p>
                                @foreach($items as $tzValue => $tzLabel)
                                    <button type="button" data-ics-dropdown="timezone" data-value="{{ $tzValue }}" data-label="{{ $tzLabel }}"
                                        class="w-full text-left py-2 px-3 rounded-lg text-sm transition-colors text-gray-300 hover:bg-neutral-700 hover:text-white">
                                        {{ $tzLabel }}
                                    </button>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Lembrete --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Lembrete</label>
                    <input type="hidden" id="ics-reminder" value="15">
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="ics-reminder-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Selecionar lembrete">
                            <span id="ics-reminder-label" class="truncate">15 minutos antes</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform shrink-0"></i>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                            role="menu" aria-orientation="vertical">
                            @foreach($reminderOptions as $value => $label)
                                <button type="button" data-ics-dropdown="reminder" data-value="{{ $value }}" data-label="{{ $label }}"
                                    class="w-full text-left py-2 px-3 rounded-lg text-sm transition-colors text-gray-300 hover:bg-neutral-700 hover:text-white">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Recorrência --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Recorrência</label>
                    <input type="hidden" id="ics-recurrence" value="">
                    <div class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]">
                        <button id="ics-recurrence-dropdown" type="button"
                            class="hs-dropdown-toggle w-full py-3 px-4 inline-flex justify-between items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-white hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-bulma-primary transition-all"
                            aria-haspopup="menu" aria-expanded="false" aria-label="Selecionar recorrência">
                            <span id="ics-recurrence-label" class="truncate">Não repete</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 hs-dropdown-open:rotate-180 transition-transform shrink-0"></i>
                        </button>
                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden w-full bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                            role="menu" aria-orientation="vertical">
                            @foreach($recurrenceOptions as $value => $label)
                                <button type="button" data-ics-dropdown="recurrence" data-value="{{ $value }}" data-label="{{ $label }}"
                                    class="w-full text-left py-2 px-3 rounded-lg text-sm transition-colors text-gray-300 hover:bg-neutral-700 hover:text-white">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div id="ics-recur-until-wrap" class="hidden">
                <label for="ics-recur-until" class="block text-sm font-medium text-gray-300 mb-2">
                    Termina em <span class="text-gray-500 font-normal">— opcional</span>
                </label>
                <input type="date" id="ics-recur-until"
                    class="w-full py-3 px-4 rounded-lg border border-neutral-600 bg-neutral-700 text-white focus:outline-none focus:ring-2 focus:ring-bulma-primary focus:border-transparent transition-all text-sm">
            </div>

            <div id="ics-status" class="hidden py-2 px-3 rounded-lg text-sm font-medium"></div>
        </div>

        {{-- Card 2: Output principal --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6 space-y-5">
            <div>
                <div class="flex items-center justify-between mb-3 gap-2">
                    <h2 class="text-lg font-semibold text-white">Arquivo .ics</h2>
                    <div class="flex items-center gap-2">
                        <button type="button" id="ics-copy"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                        <button type="button" id="ics-download"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-bulma-primary/40 bg-bulma-primary/10 text-bulma-primary hover:bg-bulma-primary/20 transition-all">
                            <i data-lucide="download" class="w-3 h-3"></i>
                            Baixar
                        </button>
                    </div>
                </div>
                <pre id="ics-output" class="block py-3 px-4 bg-neutral-900 rounded-lg text-xs text-gray-300 font-mono whitespace-pre overflow-x-auto max-h-64">—</pre>
            </div>

            <div class="border-t border-neutral-700/50 pt-5">
                <div class="flex items-center justify-between mb-3 gap-2">
                    <h2 class="text-lg font-semibold text-white">Link compartilhável</h2>
                    <div class="flex items-center gap-2">
                        <a id="ics-permalink-open" target="_blank" rel="noopener"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all opacity-50 pointer-events-none">
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                            Abrir
                        </a>
                        <button type="button" id="ics-permalink-copy"
                            class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                            <i data-lucide="copy" class="w-3 h-3"></i>
                            Copiar
                        </button>
                    </div>
                </div>
                <code id="ics-permalink" class="block py-3 px-4 bg-neutral-900 rounded-lg text-sm text-gray-300 font-mono break-all">—</code>
                <p class="text-xs text-gray-500 mt-2">
                    A URL guarda o evento no próprio link (no <code>#</code>). Quem abre vê o form preenchido e baixa o .ics.
                </p>
            </div>
        </div>

        {{-- Card 3: Por provedor --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Adicionar direto no calendário</h2>
            <ul class="divide-y divide-neutral-700/50" data-providers>
                @php
                    $providers = [
                        ['key' => 'google', 'label' => 'Google Calendar', 'icon' => 'calendar'],
                        ['key' => 'outlook', 'label' => 'Outlook.com (pessoal)', 'icon' => 'calendar'],
                        ['key' => 'office365', 'label' => 'Office 365 (corporativo)', 'icon' => 'calendar'],
                        ['key' => 'yahoo', 'label' => 'Yahoo Calendar', 'icon' => 'calendar'],
                    ];
                @endphp
                @foreach ($providers as $p)
                    <li class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-3" data-provider="{{ $p['key'] }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <i data-lucide="{{ $p['icon'] }}" class="w-4 h-4 text-gray-400 shrink-0"></i>
                            <span class="text-sm text-gray-300 truncate">{{ $p['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" data-action="copy"
                                class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                                <i data-lucide="copy" class="w-3 h-3"></i>
                                Copiar URL
                            </button>
                            <a data-action="open" target="_blank" rel="noopener"
                                class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all opacity-50 pointer-events-none">
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                                Abrir
                            </a>
                        </div>
                    </li>
                @endforeach
                <li class="py-3 last:pb-0 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400 shrink-0"></i>
                        <span class="text-sm text-gray-300 truncate">Apple Calendar</span>
                    </div>
                    <button type="button" id="ics-apple-download"
                        class="py-1.5 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                        <i data-lucide="download" class="w-3 h-3"></i>
                        Baixar .ics
                    </button>
                </li>
            </ul>
        </div>

        {{-- Card 4: Dica --}}
        <div class="bg-neutral-800/30 border border-neutral-700/30 rounded-xl p-4 sm:p-6 text-sm text-gray-400 space-y-2">
            <p>
                <strong class="text-bulma-primary">Como funciona:</strong> o arquivo <code>.ics</code> é o padrão
                aberto (RFC 5545) para eventos de calendário. Google, Apple, Outlook e Yahoo abrem nativamente.
                Para Apple Calendar na web, baixe o arquivo: não existe URL pública para criar evento direto.
            </p>
            <p>
                A geração é 100% no navegador. Nada é enviado para servidor.
                O link compartilhável carrega o evento dentro do próprio <code>#</code> da URL.
            </p>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/tools/ics-generator.js'])
    @endpush
@endsection
