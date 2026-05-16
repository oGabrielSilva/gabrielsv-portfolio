// Codes that the browser would otherwise consume (scroll, navigate, focus
// changes). preventDefault is restricted to these so shortcuts like
// Ctrl+R / Ctrl+W / F12 keep working.
const PREVENTABLE_CODES = new Set([
    'Tab', 'Space', 'Backspace', 'Enter',
    'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
    'PageUp', 'PageDown', 'Home', 'End',
    "'", '/', "Slash", "Quote",
]);

function shouldPreventDefault(e) {
    if (e.ctrlKey || e.metaKey || e.altKey) return false;
    return PREVENTABLE_CODES.has(e.code);
}

class KeyboardTester {
    constructor() {
        this.infoKey = document.getElementById('info-key');
        this.infoCode = document.getElementById('info-code');
        this.infoKeycode = document.getElementById('info-keycode');
        this.infoLocation = document.getElementById('info-location');
        this.testedCount = document.getElementById('tested-count');
        this.resetBtn = document.getElementById('reset-btn');

        this.testedKeys = new Set();
        this.keyElements = {};

        this.LOCATIONS = ['Standard', 'Left', 'Right', 'Numpad'];

        this.init();
    }

    init() {
        // Build key map
        document.querySelectorAll('.kb-key[data-code]').forEach(el => {
            this.keyElements[el.dataset.code] = el;
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
    }

    handleKeyDown(e) {
        const code = e.code;

        // Update info panel
        this.infoKey.textContent = e.key === ' ' ? 'Space' : e.key;
        this.infoCode.textContent = code;
        this.infoKeycode.textContent = e.keyCode;
        this.infoLocation.textContent = this.LOCATIONS[e.location] || e.location;

        // Highlight key
        const el = this.keyElements[code];
        if (el) {
            el.classList.add('active');

            // Mark as tested
            if (!this.testedKeys.has(code)) {
                this.testedKeys.add(code);
                el.classList.add('tested');
                this.testedCount.textContent = this.testedKeys.size;
            }
        }
    }

    handleKeyUp(e) {
        const el = this.keyElements[e.code];
        if (el) {
            el.classList.remove('active');
        }
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
