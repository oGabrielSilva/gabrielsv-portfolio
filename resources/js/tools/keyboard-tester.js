// Bloqueia o comportamento default de TODAS as teclas, exceto quando há
// modificador (Ctrl/Meta/Alt) — para você ainda conseguir sair com Ctrl+W,
// abrir DevTools com Ctrl+Shift+I, recarregar com Ctrl+R, etc.
// F11 (fullscreen) e F12 (devtools) são interceptados pelo SO antes do JS em
// alguns navegadores — não há como impedir.
function shouldPreventDefault(e) {
    if (e.ctrlKey || e.metaKey || e.altKey) return false;
    return true;
}

// Mapas estáticos por layout (usados quando usuário escolhe manualmente
// ou quando navigator.keyboard.getLayoutMap não está disponível)
const LAYOUT_OVERRIDES = {
    ansi: {
        Backquote: '`',
        Minus: '-', Equal: '=',
        BracketLeft: '[', BracketRight: ']', Backslash: '\\',
        Semicolon: ';', Quote: "'",
        Comma: ',', Period: '.', Slash: '/',
    },
    abnt2: {
        Backquote: "'",
        Minus: '-', Equal: '=',
        BracketLeft: '´',     // ´ (acento agudo)
        BracketRight: '[',
        Backslash: ']',
        Semicolon: 'Ç',       // Ç (cedilha)
        Quote: '~',
        Comma: ',', Period: '.', Slash: ';',
        IntlBackslash: '\\',
        IntlRo: '/',
    },
};

const STORAGE_KEY = 'kb-tester-layout';

class KeyboardTester {
    constructor() {
        this.infoKey = document.getElementById('info-key');
        this.infoCode = document.getElementById('info-code');
        this.infoKeycode = document.getElementById('info-keycode');
        this.infoLocation = document.getElementById('info-location');
        this.testedCount = document.getElementById('tested-count');
        this.resetBtn = document.getElementById('reset-btn');
        this.layoutSelector = document.getElementById('layout-selector');
        this.layoutLabel = document.getElementById('layout-label');
        this.layoutOptions = document.querySelectorAll('.layout-option');
        this.layoutStatus = document.getElementById('layout-status');
        this.keyboard = document.getElementById('keyboard');

        this.testedKeys = new Set();
        this.keyElements = {};
        this.originalLabels = {};

        this.LOCATIONS = ['Standard', 'Left', 'Right', 'Numpad'];

        this.init();
    }

    init() {
        document.querySelectorAll('.kb-key[data-code]').forEach(el => {
            this.keyElements[el.dataset.code] = el;
            this.originalLabels[el.dataset.code] = el.textContent;
        });

        document.addEventListener('keydown', (e) => {
            if (shouldPreventDefault(e)) e.preventDefault();
            this.handleKeyDown(e);
        });

        document.addEventListener('keyup', (e) => {
            if (shouldPreventDefault(e)) e.preventDefault();
            this.handleKeyUp(e);
        });

        this.resetBtn?.addEventListener('click', () => this.reset());

        this.layoutOptions.forEach((opt) => {
            opt.addEventListener('click', () => {
                const choice = opt.dataset.layoutOption;
                this.selectLayoutOption(opt, choice);
                try { localStorage.setItem(STORAGE_KEY, choice); } catch (_) {}
                this.applyLayout(choice);
            });
        });

        // Restaura preferência salva ou usa auto
        let saved = 'auto';
        try { saved = localStorage.getItem(STORAGE_KEY) || 'auto'; } catch (_) {}
        const savedOpt = Array.from(this.layoutOptions).find(o => o.dataset.layoutOption === saved);
        if (savedOpt) this.selectLayoutOption(savedOpt, saved);
        this.applyLayout(saved);
    }

    selectLayoutOption(opt, choice) {
        if (this.layoutSelector) this.layoutSelector.dataset.layoutValue = choice;
        if (this.layoutLabel) this.layoutLabel.textContent = opt.textContent.trim();
        this.layoutOptions.forEach((o) => {
            const active = o === opt;
            o.classList.toggle('text-bulma-primary', active);
            o.classList.toggle('bg-bulma-primary/10', active);
            o.classList.toggle('text-gray-300', !active);
            o.classList.toggle('hover:text-white', !active);
        });
    }

    async applyLayout(name) {
        if (name === 'auto') {
            const detected = await this.tryDetectLayout();
            if (detected) {
                this.setLayoutLabels(detected.labels);
                this.setLayoutFlag(detected.layout);
                this.setStatus(`auto (${detected.layout || 'API'})`);
                return;
            }
            // Fallback: sem suporte, mostra ANSI e avisa
            this.setLayoutLabels(LAYOUT_OVERRIDES.ansi);
            this.setLayoutFlag('ansi');
            this.setStatus('ANSI (auto não suportado)');
            return;
        }

        const overrides = LAYOUT_OVERRIDES[name];
        if (overrides) {
            this.setLayoutLabels(overrides);
            this.setLayoutFlag(name);
            this.setStatus(name === 'abnt2' ? 'ABNT2 (BR)' : 'ANSI (US)');
        }
    }

    async tryDetectLayout() {
        if (!navigator.keyboard || typeof navigator.keyboard.getLayoutMap !== 'function') {
            return null;
        }
        try {
            const map = await navigator.keyboard.getLayoutMap();
            const labels = {};
            for (const code of Object.keys(this.keyElements)) {
                const v = map.get(code);
                if (v) labels[code] = v.length === 1 ? v.toUpperCase() : v;
            }
            // Heurística: se Semicolon mapeia para "ç" é ABNT2; senão ANSI
            const layout = (map.get('Semicolon') || '').toLowerCase() === 'ç' ? 'abnt2' : 'ansi';
            return { labels, layout };
        } catch (_) {
            return null;
        }
    }

    setLayoutLabels(labels) {
        for (const [code, el] of Object.entries(this.keyElements)) {
            if (labels[code]) {
                el.textContent = labels[code];
            } else if (this.originalLabels[code]) {
                el.textContent = this.originalLabels[code];
            }
        }
    }

    setLayoutFlag(layout) {
        if (this.keyboard) {
            this.keyboard.dataset.layout = layout;
        }
    }

    setStatus(text) {
        if (this.layoutStatus) this.layoutStatus.textContent = text;
    }

    handleKeyDown(e) {
        const code = e.code;

        this.infoKey.textContent = e.key === ' ' ? 'Space' : e.key;
        this.infoCode.textContent = code;
        this.infoKeycode.textContent = e.keyCode;
        this.infoLocation.textContent = this.LOCATIONS[e.location] || e.location;

        const el = this.keyElements[code];
        if (el) {
            el.classList.add('active');
            if (!this.testedKeys.has(code)) {
                this.testedKeys.add(code);
                el.classList.add('tested');
                this.testedCount.textContent = this.testedKeys.size;
            }
        }
    }

    handleKeyUp(e) {
        const el = this.keyElements[e.code];
        if (el) el.classList.remove('active');
    }

    reset() {
        this.testedKeys.clear();
        this.testedCount.textContent = '0';
        this.infoKey.textContent = '—';
        this.infoCode.textContent = '—';
        this.infoKeycode.textContent = '—';
        this.infoLocation.textContent = '—';

        Object.values(this.keyElements).forEach(el => {
            el.classList.remove('active', 'tested');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new KeyboardTester();
});
