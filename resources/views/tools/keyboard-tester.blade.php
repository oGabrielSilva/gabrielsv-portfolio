@extends('layouts.tools')

@section('title', 'Testador de Teclado - Keyboard Tester Online')
@section('tool_name', 'Testador de Teclado')
@section('description', 'Teste todas as teclas do seu teclado visualmente e veja os códigos das teclas')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Testador de Teclado</h1>
                <p class="text-gray-400 text-sm sm:text-base">Pressione qualquer tecla para testá-la</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400">Teclas testadas: <span id="tested-count" class="text-bulma-primary font-semibold">0</span></span>
                <button type="button" id="reset-btn"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-neutral-600 bg-neutral-700 text-gray-300 hover:bg-neutral-600 hover:text-white transition-all">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    Reset
                </button>
            </div>
        </div>

        {{-- Key Info --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 sm:p-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Key</span>
                    <span id="info-key" class="text-white font-mono text-lg">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Code</span>
                    <span id="info-code" class="text-bulma-primary font-mono text-lg">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">KeyCode</span>
                    <span id="info-keycode" class="text-gray-300 font-mono text-lg">—</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Location</span>
                    <span id="info-location" class="text-gray-300 font-mono text-lg">—</span>
                </div>
            </div>
        </div>

        {{-- Keyboard Layout --}}
        <div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-3 sm:p-6 overflow-x-auto">
            <div id="keyboard" class="min-w-[700px] space-y-1.5">
                {{-- Row 1: Function keys --}}
                <div class="flex gap-1.5">
                    <button data-code="Escape" class="kb-key w-14 h-10">Esc</button>
                    <div class="w-8"></div>
                    <button data-code="F1" class="kb-key w-10 h-10">F1</button>
                    <button data-code="F2" class="kb-key w-10 h-10">F2</button>
                    <button data-code="F3" class="kb-key w-10 h-10">F3</button>
                    <button data-code="F4" class="kb-key w-10 h-10">F4</button>
                    <div class="w-4"></div>
                    <button data-code="F5" class="kb-key w-10 h-10">F5</button>
                    <button data-code="F6" class="kb-key w-10 h-10">F6</button>
                    <button data-code="F7" class="kb-key w-10 h-10">F7</button>
                    <button data-code="F8" class="kb-key w-10 h-10">F8</button>
                    <div class="w-4"></div>
                    <button data-code="F9" class="kb-key w-10 h-10">F9</button>
                    <button data-code="F10" class="kb-key w-10 h-10">F10</button>
                    <button data-code="F11" class="kb-key w-10 h-10">F11</button>
                    <button data-code="F12" class="kb-key w-10 h-10">F12</button>
                </div>

                {{-- Row 2: Number row --}}
                <div class="flex gap-1.5">
                    <button data-code="Backquote" class="kb-key w-10 h-10">`</button>
                    <button data-code="Digit1" class="kb-key w-10 h-10">1</button>
                    <button data-code="Digit2" class="kb-key w-10 h-10">2</button>
                    <button data-code="Digit3" class="kb-key w-10 h-10">3</button>
                    <button data-code="Digit4" class="kb-key w-10 h-10">4</button>
                    <button data-code="Digit5" class="kb-key w-10 h-10">5</button>
                    <button data-code="Digit6" class="kb-key w-10 h-10">6</button>
                    <button data-code="Digit7" class="kb-key w-10 h-10">7</button>
                    <button data-code="Digit8" class="kb-key w-10 h-10">8</button>
                    <button data-code="Digit9" class="kb-key w-10 h-10">9</button>
                    <button data-code="Digit0" class="kb-key w-10 h-10">0</button>
                    <button data-code="Minus" class="kb-key w-10 h-10">-</button>
                    <button data-code="Equal" class="kb-key w-10 h-10">=</button>
                    <button data-code="Backspace" class="kb-key flex-1 h-10">⌫ Backspace</button>
                </div>

                {{-- Row 3: QWERTY --}}
                <div class="flex gap-1.5">
                    <button data-code="Tab" class="kb-key w-16 h-10">Tab ⇥</button>
                    <button data-code="KeyQ" class="kb-key w-10 h-10">Q</button>
                    <button data-code="KeyW" class="kb-key w-10 h-10">W</button>
                    <button data-code="KeyE" class="kb-key w-10 h-10">E</button>
                    <button data-code="KeyR" class="kb-key w-10 h-10">R</button>
                    <button data-code="KeyT" class="kb-key w-10 h-10">T</button>
                    <button data-code="KeyY" class="kb-key w-10 h-10">Y</button>
                    <button data-code="KeyU" class="kb-key w-10 h-10">U</button>
                    <button data-code="KeyI" class="kb-key w-10 h-10">I</button>
                    <button data-code="KeyO" class="kb-key w-10 h-10">O</button>
                    <button data-code="KeyP" class="kb-key w-10 h-10">P</button>
                    <button data-code="BracketLeft" class="kb-key w-10 h-10">[</button>
                    <button data-code="BracketRight" class="kb-key w-10 h-10">]</button>
                    <button data-code="Backslash" class="kb-key flex-1 h-10">\</button>
                </div>

                {{-- Row 4: Home row --}}
                <div class="flex gap-1.5">
                    <button data-code="CapsLock" class="kb-key w-20 h-10">Caps Lock</button>
                    <button data-code="KeyA" class="kb-key w-10 h-10">A</button>
                    <button data-code="KeyS" class="kb-key w-10 h-10">S</button>
                    <button data-code="KeyD" class="kb-key w-10 h-10">D</button>
                    <button data-code="KeyF" class="kb-key w-10 h-10">F</button>
                    <button data-code="KeyG" class="kb-key w-10 h-10">G</button>
                    <button data-code="KeyH" class="kb-key w-10 h-10">H</button>
                    <button data-code="KeyJ" class="kb-key w-10 h-10">J</button>
                    <button data-code="KeyK" class="kb-key w-10 h-10">K</button>
                    <button data-code="KeyL" class="kb-key w-10 h-10">L</button>
                    <button data-code="Semicolon" class="kb-key w-10 h-10">;</button>
                    <button data-code="Quote" class="kb-key w-10 h-10">'</button>
                    <button data-code="Enter" class="kb-key flex-1 h-10">Enter ↵</button>
                </div>

                {{-- Row 5: Shift row --}}
                <div class="flex gap-1.5">
                    <button data-code="ShiftLeft" class="kb-key w-24 h-10">⇧ Shift</button>
                    <button data-code="KeyZ" class="kb-key w-10 h-10">Z</button>
                    <button data-code="KeyX" class="kb-key w-10 h-10">X</button>
                    <button data-code="KeyC" class="kb-key w-10 h-10">C</button>
                    <button data-code="KeyV" class="kb-key w-10 h-10">V</button>
                    <button data-code="KeyB" class="kb-key w-10 h-10">B</button>
                    <button data-code="KeyN" class="kb-key w-10 h-10">N</button>
                    <button data-code="KeyM" class="kb-key w-10 h-10">M</button>
                    <button data-code="Comma" class="kb-key w-10 h-10">,</button>
                    <button data-code="Period" class="kb-key w-10 h-10">.</button>
                    <button data-code="Slash" class="kb-key w-10 h-10">/</button>
                    <button data-code="ShiftRight" class="kb-key flex-1 h-10">⇧ Shift</button>
                </div>

                {{-- Row 6: Bottom row --}}
                <div class="flex gap-1.5">
                    <button data-code="ControlLeft" class="kb-key w-16 h-10">Ctrl</button>
                    <button data-code="MetaLeft" class="kb-key w-12 h-10">⊞</button>
                    <button data-code="AltLeft" class="kb-key w-12 h-10">Alt</button>
                    <button data-code="Space" class="kb-key flex-1 h-10">Espaço</button>
                    <button data-code="AltRight" class="kb-key w-12 h-10">Alt</button>
                    <button data-code="MetaRight" class="kb-key w-12 h-10">⊞</button>
                    <button data-code="ContextMenu" class="kb-key w-12 h-10">☰</button>
                    <button data-code="ControlRight" class="kb-key w-16 h-10">Ctrl</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .kb-key {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: 500;
                color: #d4d4d4;
                background: #2a2a2a;
                border: 1px solid #404040;
                border-radius: 0.375rem;
                cursor: default;
                user-select: none;
                transition: all 0.1s ease;
                font-family: ui-monospace, SFMono-Regular, monospace;
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
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/tools/keyboard-tester.js'])
    @endpush
@endsection
