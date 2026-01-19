@extends('layouts.app')

@section('extra_head')
    <meta name="robots" content="noindex, nofollow" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        @media print {
            @page {
                size: 1280px 4967px;
                margin: 0;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;

                width: 1280px;
                height: 4967px;

                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background: #1a1a1a !important;
            }

            body>header,
            body>footer,
            .no-print {
                display: none !important;
            }

            main {
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .break-inside-avoid {
                page-break-inside: avoid;
            }

            .break-before {
                page-break-before: always;
            }
        }

        .simulated-hover {
            transform: translateY(-8px);
            border-color: #00d1b2 !important;
        }

        .simulated-btn-hover {
            transform: translateY(-4px);
            opacity: 0.9;
        }

        .simulated-focus {
            outline: none;
            box-shadow: 0 0 0 2px #1a1a1a, 0 0 0 4px #00d1b2;
        }
    </style>
@endsection

@section('content')
    <div id="app" class="px-8 py-10">

        {{-- Header --}}
        <header class="mb-16 border-b border-neutral-700 pb-12">
            <div class="flex justify-between items-start">
                <div>
                    <span
                        class="inline-block px-3 py-1 rounded-full bg-bulma-primary/10 text-bulma-primary text-xs font-bold uppercase tracking-wider mb-4 border border-bulma-primary/20">
                        Versão 1.0 • 2026
                    </span>
                    <h1 class="text-5xl md:text-6xl font-bold text-white tracking-tight mb-4">
                        Gabriel Silva
                    </h1>
                    <p class="text-2xl text-gray-400">
                        Sistema de Design & Identidade Visual
                    </p>
                </div>
                <div class="text-right hidden sm:flex flex-col items-end gap-4">
                    <div
                        class="w-16 h-16 bg-bulma-primary rounded-xl flex items-center justify-center text-neutral-900 text-3xl font-bold shadow-lg">
                        GS
                    </div>
                    <button onclick="window.print()"
                        class="no-print flex items-center gap-2 bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-all">
                        <i class="ph ph-file-pdf"></i>
                        Exportar PDF
                    </button>
                </div>
            </div>
            {{-- Mobile export button --}}
            <button onclick="window.print()"
                class="no-print sm:hidden mt-6 w-full flex items-center justify-center gap-2 bg-neutral-800 hover:bg-neutral-700 border border-neutral-700 text-white text-sm font-medium py-3 px-4 rounded-lg transition-all">
                <i class="ph ph-file-pdf"></i>
                Exportar PDF
            </button>
        </header>

        {{-- 1. Paleta de Cores --}}
        <section class="mb-16 break-inside-avoid">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-palette text-bulma-primary"></i> 1. Paleta de Cores
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                {{-- Dark Mode --}}
                <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-[#2f2f2f] relative">
                    <div
                        class="absolute top-0 right-0 bg-[#242424] px-3 py-1 text-xs font-bold text-gray-400 rounded-bl-lg rounded-tr-xl border-l border-b border-[#2f2f2f]">
                        DARK MODE (Default)
                    </div>

                    <h3 class="text-white font-bold mb-6 mt-2">Tokens</h3>

                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-bulma-primary shadow-lg"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-white font-medium">Primary</span>
                                    <span class="text-bulma-primary font-mono text-sm">#00d1b2</span>
                                </div>
                                <p class="text-gray-500 text-xs">CTAs, Destaques</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#1a1a1a] border border-[#2f2f2f]"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-white font-medium">Background</span>
                                    <span class="text-gray-400 font-mono text-sm">#1a1a1a</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#242424] border border-[#2f2f2f]"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-white font-medium">Surface</span>
                                    <span class="text-gray-400 font-mono text-sm">#242424</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#2f2f2f]"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-white font-medium">Border</span>
                                    <span class="text-gray-400 font-mono text-sm">#2f2f2f</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-white font-bold mt-6 mb-3 text-sm">Texto</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-white"></div>
                            <span class="text-white text-sm">text-primary</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#ffffff</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#a3a3a3]"></div>
                            <span class="text-gray-400 text-sm">text-secondary</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#a3a3a3</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#6b7280]"></div>
                            <span class="text-gray-500 text-sm">text-muted</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#6b7280</span>
                        </div>
                    </div>
                </div>

                {{-- Light Mode --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-200 relative">
                    <div
                        class="absolute top-0 right-0 bg-gray-100 px-3 py-1 text-xs font-bold text-gray-500 rounded-bl-lg rounded-tr-xl border-l border-b border-gray-200">
                        LIGHT MODE
                    </div>

                    <h3 class="text-gray-900 font-bold mb-6 mt-2">Tokens</h3>

                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#00a896] shadow-md"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Primary</span>
                                    <span class="text-[#00a896] font-mono text-sm">#00a896</span>
                                </div>
                                <p class="text-gray-400 text-xs">CTAs, Destaques</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-white border border-gray-200"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Background</span>
                                    <span class="text-gray-400 font-mono text-sm">#ffffff</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#f5f5f5] border border-gray-200"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Surface</span>
                                    <span class="text-gray-400 font-mono text-sm">#f5f5f5</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-[#e5e5e5]"></div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 font-medium">Border</span>
                                    <span class="text-gray-400 font-mono text-sm">#e5e5e5</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-gray-900 font-bold mt-6 mb-3 text-sm">Texto</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#1a1a1a]"></div>
                            <span class="text-gray-900 text-sm">text-primary</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#1a1a1a</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#525252]"></div>
                            <span class="text-[#525252] text-sm">text-secondary</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#525252</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-[#a3a3a3]"></div>
                            <span class="text-[#a3a3a3] text-sm">text-muted</span>
                            <span class="text-gray-400 font-mono text-xs ml-auto">#a3a3a3</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cores de Acento --}}
            <h3 class="text-lg font-bold text-gray-400 mb-4">Cores de Acento</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-center">
                    <div class="h-12 w-full bg-blue-500 rounded mb-2"></div>
                    <span class="text-sm font-bold text-white block">Blue</span>
                    <span class="text-xs font-mono text-gray-500">#3b82f6</span>
                </div>
                <div class="bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-center">
                    <div class="h-12 w-full bg-sky-400 rounded mb-2"></div>
                    <span class="text-sm font-bold text-white block">Sky</span>
                    <span class="text-xs font-mono text-gray-500">#38bdf8</span>
                </div>
                <div class="bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-center">
                    <div class="h-12 w-full bg-orange-400 rounded mb-2"></div>
                    <span class="text-sm font-bold text-white block">Orange</span>
                    <span class="text-xs font-mono text-gray-500">#fb923c</span>
                </div>
                <div class="bg-neutral-800 p-4 rounded-lg border border-neutral-700 text-center">
                    <div class="h-12 w-full bg-emerald-400 rounded mb-2"></div>
                    <span class="text-sm font-bold text-white block">Emerald</span>
                    <span class="text-xs font-mono text-gray-500">#34d399</span>
                </div>
            </div>
        </section>

        {{-- 2. Tipografia --}}
        <section class="mb-16 break-inside-avoid">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-text-aa text-bulma-primary"></i> 2. Tipografia
            </h2>

            <div class="bg-neutral-800 border border-neutral-700 rounded-2xl p-8">
                <div class="flex items-center gap-6 mb-8 pb-6 border-b border-neutral-700">
                    <span class="text-6xl font-bold text-white">Aa</span>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Google Sans Flex</h3>
                        <p class="text-gray-500">Fallback: Inter, Instrument Sans, system-ui</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        <div class="col-span-3 text-gray-500 text-sm font-mono">H1 / 700 / 48-60px</div>
                        <div class="col-span-9">
                            <span class="text-5xl font-bold text-white leading-tight">The quick brown fox</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        <div class="col-span-3 text-gray-500 text-sm font-mono">H2 / 700 / 30-36px</div>
                        <div class="col-span-9">
                            <span class="text-3xl font-bold text-white">Jumps over the lazy dog</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        <div class="col-span-3 text-gray-500 text-sm font-mono">H3 / 700 / 18-20px</div>
                        <div class="col-span-9">
                            <span class="text-xl font-bold text-white">Pack my box with five dozen liquor jugs</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        <div class="col-span-3 text-gray-500 text-sm font-mono">Body / 400 / 14-16px</div>
                        <div class="col-span-9">
                            <p class="text-base text-gray-400 leading-relaxed">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-baseline">
                        <div class="col-span-3 text-gray-500 text-sm font-mono">Small / 500 / 12px</div>
                        <div class="col-span-9">
                            <p class="text-xs font-medium text-gray-500 tracking-wide uppercase">Labels, metadados e
                                informações auxiliares</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 3. Espaçamento & Radius --}}
        <section class="mb-16 break-inside-avoid">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-ruler text-bulma-primary"></i> 3. Espaçamento & Radius
            </h2>

            <div class="grid grid-cols-2 gap-8">
                {{-- Espaçamento --}}
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-6">
                    <h3 class="font-bold text-white mb-4">Espaçamento <span class="text-gray-500 font-normal text-sm">(base:
                            4px)</span></h3>
                    <div class="space-y-3">
                        @foreach([['xs', '4px', 'w-1'], ['sm', '8px', 'w-2'], ['md', '16px', 'w-4'], ['lg', '24px', 'w-6'], ['xl', '32px', 'w-8'], ['2xl', '48px', 'w-12'], ['3xl', '64px', 'w-16'], ['4xl', '96px', 'w-24']] as $space)
                            <div class="flex items-center gap-4">
                                <span class="w-8 text-sm font-mono text-gray-500">{{ $space[0] }}</span>
                                <div class="h-4 bg-bulma-primary/30 border border-bulma-primary/50 {{ $space[2] }}"></div>
                                <span class="text-xs text-gray-500">{{ $space[1] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Radius --}}
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-6">
                    <h3 class="font-bold text-white mb-4">Border Radius</h3>
                    <div class="flex flex-wrap gap-4">
                        <div
                            class="w-20 h-20 bg-neutral-900 border border-neutral-700 flex items-center justify-center flex-col rounded text-bulma-primary font-bold text-sm">
                            sm
                            <span class="text-[10px] font-normal text-gray-500">4px</span>
                        </div>
                        <div
                            class="w-20 h-20 bg-neutral-900 border border-neutral-700 flex items-center justify-center flex-col rounded-lg text-bulma-primary font-bold text-sm">
                            md
                            <span class="text-[10px] font-normal text-gray-500">8px</span>
                        </div>
                        <div
                            class="w-20 h-20 bg-neutral-900 border border-neutral-700 flex items-center justify-center flex-col rounded-xl text-bulma-primary font-bold text-sm">
                            lg
                            <span class="text-[10px] font-normal text-gray-500">12px</span>
                        </div>
                        <div
                            class="w-20 h-20 bg-neutral-900 border border-neutral-700 flex items-center justify-center flex-col rounded-full text-bulma-primary font-bold text-sm">
                            full
                            <span class="text-[10px] font-normal text-gray-500">9999px</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. Sombras --}}
        <section class="mb-16 break-inside-avoid">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-drop-half-bottom text-bulma-primary"></i> 4. Sombras
            </h2>

            <div class="grid grid-cols-2 gap-8">
                {{-- Dark Mode --}}
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-6">
                    <h3 class="font-bold mb-6 text-sm text-gray-500 uppercase tracking-wider">Dark Mode</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="w-full h-24 bg-neutral-900 rounded-lg mb-3"
                                style="box-shadow: 0 10px 40px -10px rgba(0, 209, 178, 0.15);"></div>
                            <p class="text-sm font-mono text-gray-400">shadow-card</p>
                            <p class="text-xs text-gray-500">0 10px 40px -10px rgba(0, 209, 178, 0.15)</p>
                        </div>
                        <div>
                            <div class="w-full h-24 bg-neutral-900 rounded-lg mb-3"
                                style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);"></div>
                            <p class="text-sm font-mono text-gray-400">shadow-elevated</p>
                            <p class="text-xs text-gray-500">0 4px 20px rgba(0, 0, 0, 0.4)</p>
                        </div>
                    </div>
                </div>

                {{-- Light Mode --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="font-bold mb-6 text-sm text-gray-500 uppercase tracking-wider">Light Mode</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="w-full h-24 bg-gray-50 rounded-lg mb-3"
                                style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);"></div>
                            <p class="text-sm font-mono text-gray-500">shadow-card</p>
                            <p class="text-xs text-gray-400">0 4px 20px rgba(0, 0, 0, 0.08)</p>
                        </div>
                        <div>
                            <div class="w-full h-24 bg-gray-50 rounded-lg mb-3"
                                style="box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);"></div>
                            <p class="text-sm font-mono text-gray-500">shadow-elevated</p>
                            <p class="text-xs text-gray-400">0 8px 30px rgba(0, 0, 0, 0.12)</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. Transições & Estados --}}
        <section class="mb-16 break-inside-avoid">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-timer text-bulma-primary"></i> 5. Transições & Estados
            </h2>

            <div class="grid grid-cols-2 gap-8">
                {{-- Durações --}}
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-6">
                    <h3 class="font-bold text-white mb-4">Durações</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-neutral-700">
                                <th class="pb-2">Token</th>
                                <th class="pb-2">Duração</th>
                                <th class="pb-2">Easing</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300">
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 font-mono text-bulma-primary">fast</td>
                                <td class="py-2">150ms</td>
                                <td class="py-2 text-gray-500">ease-out</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 font-mono text-bulma-primary">normal</td>
                                <td class="py-2">300ms</td>
                                <td class="py-2 text-gray-500">ease-out</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2 font-mono text-bulma-primary">slow</td>
                                <td class="py-2">500ms</td>
                                <td class="py-2 text-gray-500">ease-out</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono text-bulma-primary">entrance</td>
                                <td class="py-2">800ms</td>
                                <td class="py-2 text-gray-500 text-xs">cubic-bezier(0.33, 1, 0.68, 1)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Estados de Interação --}}
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-6">
                    <h3 class="font-bold text-white mb-4">Estados de Interação</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b border-neutral-700">
                                <th class="pb-2">Elemento</th>
                                <th class="pb-2">Hover / Focus</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-300">
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2">Botões</td>
                                <td class="py-2 text-gray-400 text-xs">translateY(-4px), opacity 90%</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2">Cards</td>
                                <td class="py-2 text-gray-400 text-xs">translateY(-8px), border → primary</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2">Ícones</td>
                                <td class="py-2 text-gray-400 text-xs">scale(1.1) rotate(3deg)</td>
                            </tr>
                            <tr class="border-b border-neutral-700/50">
                                <td class="py-2">Inputs (focus)</td>
                                <td class="py-2 text-gray-400 text-xs">ring 2px primary, offset 2px</td>
                            </tr>
                            <tr>
                                <td class="py-2">Disabled</td>
                                <td class="py-2 text-gray-400 text-xs">opacity 50%, cursor not-allowed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- 6. Componentes & Interações --}}
        <section class="mb-16">
            <h2 class="text-3xl font-bold text-white mb-8 flex items-center gap-3 border-b border-neutral-700 pb-4">
                <i class="ph ph-cursor-click text-bulma-primary"></i> 6. Componentes & Interações
            </h2>

            {{-- Botões --}}
            <div class="mb-12">
                <h3 class="text-xl font-bold text-white mb-4 pl-4 border-l-4 border-bulma-primary">Botões</h3>
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div>
                            <span
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-neutral-700 pb-2">Normal</span>
                            <div class="flex gap-4 mb-4">
                                <div
                                    class="bg-bulma-primary text-neutral-900 font-bold py-3 px-6 rounded text-sm inline-block">
                                    Primary</div>
                                <div
                                    class="bg-neutral-800 border border-neutral-700 text-white font-bold py-3 px-6 rounded text-sm inline-block">
                                    Secondary</div>
                            </div>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li>• Primary: bg primary, texto background</li>
                                <li>• Secondary: bg surface, border border</li>
                                <li>• Padding: 12px 24px, radius sm (4px)</li>
                            </ul>
                        </div>
                        <div>
                            <span
                                class="block text-xs font-bold text-bulma-primary uppercase tracking-wider mb-4 border-b border-neutral-700 pb-2">Hover</span>
                            <div class="flex gap-4 mb-4">
                                <div
                                    class="bg-bulma-primary text-neutral-900 font-bold py-3 px-6 rounded text-sm inline-block simulated-btn-hover">
                                    Primary</div>
                                <div
                                    class="bg-neutral-900 border border-neutral-700 text-white font-bold py-3 px-6 rounded text-sm inline-block simulated-btn-hover">
                                    Secondary</div>
                            </div>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• <span class="text-bulma-primary">translateY(-4px)</span> — eleva o botão</li>
                                <li>• <span class="text-bulma-primary">opacity: 90%</span> — leve fade</li>
                                <li>• Transição: 300ms ease-out</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inputs --}}
            <div class="mb-12 break-inside-avoid">
                <h3 class="text-xl font-bold text-white mb-4 pl-4 border-l-4 border-bulma-primary">Inputs</h3>
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div>
                            <span
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-neutral-700 pb-2">Normal</span>
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email</label>
                                <div
                                    class="w-full bg-neutral-700 border border-neutral-600 text-gray-500 px-4 py-3 rounded-lg">
                                    nome@exemplo.com</div>
                            </div>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li>• Background: border (#2f2f2f)</li>
                                <li>• Border: 1px #404040</li>
                                <li>• Padding: 12px 16px, radius md (8px)</li>
                            </ul>
                        </div>
                        <div>
                            <span
                                class="block text-xs font-bold text-bulma-primary uppercase tracking-wider mb-4 border-b border-neutral-700 pb-2">Focus</span>
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email</label>
                                <div
                                    class="w-full bg-neutral-700 border border-neutral-600 text-white px-4 py-3 rounded-lg simulated-focus relative">
                                    nome@exemplo.com<span class="animate-pulse">|</span>
                                </div>
                            </div>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• <span class="text-bulma-primary">ring: 2px primary</span></li>
                                <li>• <span class="text-bulma-primary">offset: 2px</span> (espaço entre borda e ring)</li>
                                <li>• Texto muda para text-primary</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="mb-12 break-inside-avoid">
                <h3 class="text-xl font-bold text-white mb-4 pl-4 border-l-4 border-bulma-primary">Cards</h3>
                <div class="bg-neutral-800 border border-neutral-700 rounded-xl p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div>
                            <div class="bg-neutral-800/50 border border-neutral-700 p-6 rounded-xl mb-4">
                                <span
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Normal</span>
                                <div
                                    class="w-10 h-10 bg-bulma-primary/10 rounded-lg flex items-center justify-center text-bulma-primary mb-3">
                                    <i class="ph ph-star text-xl"></i>
                                </div>
                                <h5 class="text-lg font-bold text-white mb-1">Título do Card</h5>
                                <p class="text-gray-400 text-sm">Descrição breve do conteúdo.</p>
                            </div>
                            <ul class="text-xs text-gray-500 space-y-1">
                                <li>• Background: surface 50% opacity</li>
                                <li>• Border: 1px border</li>
                                <li>• Padding: 24-32px, radius lg (12px)</li>
                            </ul>
                        </div>
                        <div>
                            <div
                                class="bg-neutral-800/50 border border-neutral-700 p-6 rounded-xl simulated-hover relative mb-4">
                                <div
                                    class="absolute -top-3 -right-3 bg-bulma-primary text-neutral-900 text-[10px] px-2 py-1 rounded font-bold shadow-lg z-10">
                                    HOVER</div>
                                <span
                                    class="block text-xs font-bold text-bulma-primary uppercase tracking-wider mb-4">Hover</span>
                                <div
                                    class="w-10 h-10 bg-bulma-primary/10 rounded-lg flex items-center justify-center text-bulma-primary mb-3 transform scale-110 rotate-3">
                                    <i class="ph ph-star text-xl"></i>
                                </div>
                                <h5 class="text-lg font-bold text-white mb-1">Título do Card</h5>
                                <p class="text-gray-400 text-sm">Descrição breve do conteúdo.</p>
                            </div>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• <span class="text-bulma-primary">translateY(-8px)</span> — eleva o card</li>
                                <li>• <span class="text-bulma-primary">border → primary</span></li>
                                <li>• Ícone: <span class="text-bulma-primary">scale(1.1) rotate(3deg)</span></li>
                                <li>• Transição: 300ms ease-out</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="text-center text-gray-500 text-sm py-8 border-t border-neutral-700 mt-12">
            <p>© {{ date('Y') }} Gabriel Silva — Brand Guide v1.0</p>
        </footer>
    </div>
@endsection