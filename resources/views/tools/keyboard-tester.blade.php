@extends('layouts.tools')

@section('title', 'Testador de Teclado Online (ABNT2 e ANSI): tecla presa, falha, KeyCode')
@section('tool_name', 'Testador de Teclado')
@section('description', 'Pressiona uma tecla e ela acende na tela. Para testar teclado novo antes de comprar, achar tecla presa ou ver o KeyCode no JavaScript. Suporta ABNT2 e ANSI.')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Testador de Teclado</h1>
                <p class="text-gray-400 text-sm sm:text-base">Pressiona uma tecla e ela acende na tela. Detecta tecla presa, falha intermitente e mostra o KeyCode.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <div id="layout-selector" class="hs-dropdown relative [--strategy:absolute] [--adaptive:none]" data-layout-value="auto">
                    <button id="layout-dropdown" type="button"
                        class="hs-dropdown-toggle py-1.5 px-2.5 inline-flex items-center gap-x-2 text-xs sm:text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-200 hover:bg-neutral-600 transition-all"
                        aria-haspopup="menu" aria-expanded="false">
                        <span id="layout-label">Detectar (auto)</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 sm:w-4 sm:h-4 hs-dropdown-open:rotate-180 transition-transform"></i>
                    </button>
                    <div
                        class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[10rem] bg-neutral-800 shadow-md rounded-lg p-2 mt-2 border border-neutral-700 z-50"
                        role="menu" aria-labelledby="layout-dropdown">
                        <button type="button" data-layout-option="auto"
                            class="layout-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-bulma-primary bg-bulma-primary/10 hover:bg-neutral-700">
                            <span>Detectar (auto)</span>
                        </button>
                        <button type="button" data-layout-option="abnt2"
                            class="layout-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                            <span>ABNT2 (BR)</span>
                        </button>
                        <button type="button" data-layout-option="ansi"
                            class="layout-option w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-300 hover:bg-neutral-700 hover:text-white">
                            <span>ANSI (US)</span>
                        </button>
                    </div>
                </div>
                <span class="text-xs sm:text-sm text-gray-400 hidden sm:inline">
                    Layout: <span id="layout-status" class="text-bulma-primary font-semibold">—</span>
                </span>
                <span class="text-xs sm:text-sm text-gray-400">Testadas: <span id="tested-count" class="text-bulma-primary font-semibold">0</span></span>
                <button type="button" id="reset-btn"
                    class="py-1.5 px-2.5 sm:py-2 sm:px-3 inline-flex items-center gap-x-2 text-xs sm:text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                    Reset
                </button>
            </div>
        </div>

        {{-- Key Info --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-3 sm:p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Key</span>
                    <span id="info-key" class="text-white font-mono text-base sm:text-lg break-all">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Code</span>
                    <span id="info-code" class="text-bulma-primary font-mono text-base sm:text-lg break-all">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">KeyCode</span>
                    <span id="info-keycode" class="text-gray-300 font-mono text-base sm:text-lg">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Location</span>
                    <span id="info-location" class="text-gray-300 font-mono text-base sm:text-lg">—</span>
                </div>
            </div>
        </div>

        {{-- Keyboard Layout --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-3 sm:p-6">
            <div id="keyboard" class="kb-container flex flex-wrap gap-3 sm:gap-4 justify-center lg:justify-start">
                {{-- Main cluster --}}
                <div class="kb-main flex flex-col gap-1 sm:gap-1.5">
                    {{-- Row 1: Function keys --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Escape" class="kb-key kb-wide">Esc</button>
                        <div class="kb-spacer-md"></div>
                        <button data-code="F1" class="kb-key">F1</button>
                        <button data-code="F2" class="kb-key">F2</button>
                        <button data-code="F3" class="kb-key">F3</button>
                        <button data-code="F4" class="kb-key">F4</button>
                        <div class="kb-spacer-sm"></div>
                        <button data-code="F5" class="kb-key">F5</button>
                        <button data-code="F6" class="kb-key">F6</button>
                        <button data-code="F7" class="kb-key">F7</button>
                        <button data-code="F8" class="kb-key">F8</button>
                        <div class="kb-spacer-sm"></div>
                        <button data-code="F9" class="kb-key">F9</button>
                        <button data-code="F10" class="kb-key">F10</button>
                        <button data-code="F11" class="kb-key">F11</button>
                        <button data-code="F12" class="kb-key">F12</button>
                    </div>

                    {{-- Row 2: Number row --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Backquote" class="kb-key">`</button>
                        <button data-code="Digit1" class="kb-key">1</button>
                        <button data-code="Digit2" class="kb-key">2</button>
                        <button data-code="Digit3" class="kb-key">3</button>
                        <button data-code="Digit4" class="kb-key">4</button>
                        <button data-code="Digit5" class="kb-key">5</button>
                        <button data-code="Digit6" class="kb-key">6</button>
                        <button data-code="Digit7" class="kb-key">7</button>
                        <button data-code="Digit8" class="kb-key">8</button>
                        <button data-code="Digit9" class="kb-key">9</button>
                        <button data-code="Digit0" class="kb-key">0</button>
                        <button data-code="Minus" class="kb-key">-</button>
                        <button data-code="Equal" class="kb-key">=</button>
                        <button data-code="Backspace" class="kb-key flex-1">⌫ Backspace</button>
                    </div>

                    {{-- Row 3: QWERTY --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Tab" class="kb-key kb-wide">Tab ⇥</button>
                        <button data-code="KeyQ" class="kb-key">Q</button>
                        <button data-code="KeyW" class="kb-key">W</button>
                        <button data-code="KeyE" class="kb-key">E</button>
                        <button data-code="KeyR" class="kb-key">R</button>
                        <button data-code="KeyT" class="kb-key">T</button>
                        <button data-code="KeyY" class="kb-key">Y</button>
                        <button data-code="KeyU" class="kb-key">U</button>
                        <button data-code="KeyI" class="kb-key">I</button>
                        <button data-code="KeyO" class="kb-key">O</button>
                        <button data-code="KeyP" class="kb-key">P</button>
                        <button data-code="BracketLeft" class="kb-key">[</button>
                        <button data-code="BracketRight" class="kb-key">]</button>
                        <button data-code="Backslash" class="kb-key flex-1">\</button>
                    </div>

                    {{-- Row 4: Home row --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="CapsLock" class="kb-key kb-extra-wide">Caps Lock</button>
                        <button data-code="KeyA" class="kb-key">A</button>
                        <button data-code="KeyS" class="kb-key">S</button>
                        <button data-code="KeyD" class="kb-key">D</button>
                        <button data-code="KeyF" class="kb-key">F</button>
                        <button data-code="KeyG" class="kb-key">G</button>
                        <button data-code="KeyH" class="kb-key">H</button>
                        <button data-code="KeyJ" class="kb-key">J</button>
                        <button data-code="KeyK" class="kb-key">K</button>
                        <button data-code="KeyL" class="kb-key">L</button>
                        <button data-code="Semicolon" class="kb-key">;</button>
                        <button data-code="Quote" class="kb-key">'</button>
                        <button data-code="Enter" class="kb-key flex-1">Enter ↵</button>
                    </div>

                    {{-- Row 5: Shift row --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="ShiftLeft" class="kb-key kb-shift">⇧ Shift</button>
                        <button data-code="IntlBackslash" class="kb-key kb-abnt2-only" data-abnt2-label="\" title="Tecla extra ABNT2">\</button>
                        <button data-code="KeyZ" class="kb-key">Z</button>
                        <button data-code="KeyX" class="kb-key">X</button>
                        <button data-code="KeyC" class="kb-key">C</button>
                        <button data-code="KeyV" class="kb-key">V</button>
                        <button data-code="KeyB" class="kb-key">B</button>
                        <button data-code="KeyN" class="kb-key">N</button>
                        <button data-code="KeyM" class="kb-key">M</button>
                        <button data-code="Comma" class="kb-key">,</button>
                        <button data-code="Period" class="kb-key">.</button>
                        <button data-code="Slash" class="kb-key">/</button>
                        <button data-code="IntlRo" class="kb-key kb-abnt2-only" data-abnt2-label="/" title="Tecla extra ABNT2 (numérico/divisão)">/</button>
                        <button data-code="ShiftRight" class="kb-key flex-1">⇧ Shift</button>
                    </div>

                    {{-- Row 6: Bottom row --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="ControlLeft" class="kb-key kb-wide">Ctrl</button>
                        <button data-code="MetaLeft" class="kb-key">⊞</button>
                        <button data-code="AltLeft" class="kb-key">Alt</button>
                        <button data-code="Space" class="kb-key flex-1">Espaço</button>
                        <button data-code="AltRight" class="kb-key">Alt</button>
                        <button data-code="MetaRight" class="kb-key">⊞</button>
                        <button data-code="ContextMenu" class="kb-key">☰</button>
                        <button data-code="ControlRight" class="kb-key kb-wide">Ctrl</button>
                    </div>
                </div>

                {{-- Navigation cluster --}}
                <div class="kb-nav flex flex-col gap-1 sm:gap-1.5">
                    {{-- Row 1: PrintScreen / ScrollLock / Pause --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="PrintScreen" class="kb-key">PrtSc</button>
                        <button data-code="ScrollLock" class="kb-key">ScrLk</button>
                        <button data-code="Pause" class="kb-key">Pause</button>
                    </div>

                    {{-- Row 2: Insert / Home / PageUp --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Insert" class="kb-key">Ins</button>
                        <button data-code="Home" class="kb-key">Home</button>
                        <button data-code="PageUp" class="kb-key">PgUp</button>
                    </div>

                    {{-- Row 3: Delete / End / PageDown --}}
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Delete" class="kb-key">Del</button>
                        <button data-code="End" class="kb-key">End</button>
                        <button data-code="PageDown" class="kb-key">PgDn</button>
                    </div>

                    <div class="kb-spacer-row"></div>

                    {{-- Empty row for arrow layout --}}
                    <div class="flex gap-1 sm:gap-1.5 justify-center">
                        <div class="kb-key kb-invisible"></div>
                        <button data-code="ArrowUp" class="kb-key">↑</button>
                        <div class="kb-key kb-invisible"></div>
                    </div>

                    {{-- Arrows --}}
                    <div class="flex gap-1 sm:gap-1.5 justify-center">
                        <button data-code="ArrowLeft" class="kb-key">←</button>
                        <button data-code="ArrowDown" class="kb-key">↓</button>
                        <button data-code="ArrowRight" class="kb-key">→</button>
                    </div>
                </div>

                {{-- Numpad cluster --}}
                <div class="kb-numpad flex flex-col gap-1 sm:gap-1.5">
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="NumLock" class="kb-key">Num</button>
                        <button data-code="NumpadDivide" class="kb-key">/</button>
                        <button data-code="NumpadMultiply" class="kb-key">*</button>
                        <button data-code="NumpadSubtract" class="kb-key">-</button>
                    </div>
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Numpad7" class="kb-key">7</button>
                        <button data-code="Numpad8" class="kb-key">8</button>
                        <button data-code="Numpad9" class="kb-key">9</button>
                        <button data-code="NumpadAdd" class="kb-key kb-numpad-tall">+</button>
                    </div>
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Numpad4" class="kb-key">4</button>
                        <button data-code="Numpad5" class="kb-key">5</button>
                        <button data-code="Numpad6" class="kb-key">6</button>
                        <div class="kb-key kb-invisible"></div>
                    </div>
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Numpad1" class="kb-key">1</button>
                        <button data-code="Numpad2" class="kb-key">2</button>
                        <button data-code="Numpad3" class="kb-key">3</button>
                        <button data-code="NumpadEnter" class="kb-key kb-numpad-tall">↵</button>
                    </div>
                    <div class="flex gap-1 sm:gap-1.5">
                        <button data-code="Numpad0" class="kb-key kb-numpad-wide">0</button>
                        <button data-code="NumpadDecimal" class="kb-key">.</button>
                        <div class="kb-key kb-invisible"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /*
             * Dimensão base de uma tecla, definida via custom property para que
             * .kb-key, .kb-wide, .kb-numpad-tall e .kb-numpad-wide derivem dela.
             * Escala fluida: ~30px em mobile estreito → 40px em desktop.
             */
            .kb-container {
                --kb-size: clamp(1.7rem, 2.6vw, 2.5rem);
                --kb-gap: 0.25rem;
                --kb-font: clamp(0.55rem, 0.85vw, 0.7rem);
            }
            @media (min-width: 640px) {
                .kb-container { --kb-gap: 0.375rem; }
            }

            .kb-key {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: var(--kb-size);
                height: var(--kb-size);
                font-size: var(--kb-font);
                font-weight: 500;
                color: #d4d4d4;
                background: #2a2a2a;
                border: 1px solid #404040;
                border-radius: 0.375rem;
                cursor: default;
                user-select: none;
                transition: all 0.1s ease;
                font-family: ui-monospace, SFMono-Regular, monospace;
                padding: 0 0.15rem;
                white-space: nowrap;
                appearance: none;
                -webkit-appearance: none;
            }

            .kb-wide { width: calc(var(--kb-size) * 1.6); }
            .kb-extra-wide { width: calc(var(--kb-size) * 2); }
            .kb-shift { width: calc(var(--kb-size) * 2.4); }

            .kb-spacer-sm { width: calc(var(--kb-size) * 0.4); }
            .kb-spacer-md { width: calc(var(--kb-size) * 0.8); }
            .kb-spacer-row { height: calc(var(--kb-size) * 0.6); }

            /* Numpad: + e Enter ocupam 2 linhas */
            .kb-numpad-tall { height: calc(var(--kb-size) * 2 + var(--kb-gap)); }
            /* Numpad: 0 ocupa 2 colunas */
            .kb-numpad-wide { width: calc(var(--kb-size) * 2 + var(--kb-gap)); }

            /* Espaçador invisível mantendo grade */
            .kb-invisible {
                background: transparent;
                border-color: transparent;
                pointer-events: none;
            }

            .kb-key.active {
                background: #00d1b2;
                border-color: #00e5c4;
                color: #1a1a1a;
                transform: scale(0.95);
                box-shadow: 0 0 12px rgba(0, 209, 178, 0.4);
            }
            .kb-key.tested {
                background: #1a2e2b;
                border-color: #00d1b2;
                color: #00d1b2;
            }
            .kb-key.tested.active {
                background: #00d1b2;
                border-color: #00e5c4;
                color: #1a1a1a;
            }
            .kb-invisible.active, .kb-invisible.tested {
                background: transparent;
                border-color: transparent;
                box-shadow: none;
            }

            /* Teclas ABNT2 só aparecem quando layout ABNT2 selecionado */
            .kb-abnt2-only { display: none; }
            .kb-container[data-layout="abnt2"] .kb-abnt2-only { display: inline-flex; }
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/tools/keyboard-tester.js'])
    @endpush
@endsection
