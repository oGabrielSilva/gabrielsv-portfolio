import { escapeHtml, escapeAttr } from '../utils/dom.js';
import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

class PasswordGenerator {
    constructor() {
        this.passwordDisplay = document.getElementById('password-display');
        this.copyBtn = document.getElementById('copy-btn');
        this.refreshBtn = document.getElementById('refresh-btn');
        this.lengthSlider = document.getElementById('length-slider');
        this.lengthInput = document.getElementById('length-input');
        this.strengthBar = document.getElementById('strength-bar');
        this.strengthLabel = document.getElementById('strength-label');
        this.optUpper = document.getElementById('opt-upper');
        this.optLower = document.getElementById('opt-lower');
        this.optNumbers = document.getElementById('opt-numbers');
        this.optSymbols = document.getElementById('opt-symbols');
        this.optExcludeAmbiguous = document.getElementById('opt-exclude-ambiguous');
        this.generateMultiBtn = document.getElementById('generate-multi-btn');
        this.multiCount = document.getElementById('multi-count');
        this.multiList = document.getElementById('multi-list');

        this.UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        this.LOWER = 'abcdefghijklmnopqrstuvwxyz';
        this.NUMBERS = '0123456789';
        this.SYMBOLS = '!@#$%^&*()_+-=[]{}|;:,.<>?/~`';
        this.AMBIGUOUS = '0OolI1|`\'"~,;:.<>';

        this.init();
    }

    init() {
        this.refreshBtn?.addEventListener('click', () => this.generate());
        this.copyBtn?.addEventListener('click', () => this.copy(this.passwordDisplay.value));
        this.lengthSlider?.addEventListener('input', () => {
            this.lengthInput.value = this.lengthSlider.value;
            this.generate();
        });
        this.lengthInput?.addEventListener('input', () => {
            let val = parseInt(this.lengthInput.value) || 8;
            val = Math.max(4, Math.min(128, val));
            this.lengthSlider.value = val;
            this.generate();
        });

        [this.optUpper, this.optLower, this.optNumbers, this.optSymbols, this.optExcludeAmbiguous].forEach(el => {
            el?.addEventListener('change', () => this.generate());
        });

        this.generateMultiBtn?.addEventListener('click', () => this.generateMultiple());

        // Generate initial password
        this.generate();
    }

    getCharset() {
        return this.getActiveCategories().join('');
    }

    getActiveCategories() {
        const filter = (chars) => this.optExcludeAmbiguous.checked
            ? chars.split('').filter(c => !this.AMBIGUOUS.includes(c)).join('')
            : chars;

        const cats = [];
        if (this.optUpper.checked) cats.push(filter(this.UPPER));
        if (this.optLower.checked) cats.push(filter(this.LOWER));
        if (this.optNumbers.checked) cats.push(filter(this.NUMBERS));
        if (this.optSymbols.checked) cats.push(filter(this.SYMBOLS));
        return cats.filter(c => c.length > 0);
    }

    generate() {
        const charset = this.getCharset();
        if (!charset) {
            this.passwordDisplay.value = 'Selecione ao menos uma opção';
            this.updateStrength('');
            this.setCopyEnabled(false);
            this.generateMultiBtn && (this.generateMultiBtn.disabled = true);
            return;
        }

        const length = parseInt(this.lengthSlider.value) || 16;
        const password = this.generatePassword(charset, length);
        this.passwordDisplay.value = password;
        this.updateStrength(password);
        this.setCopyEnabled(true);
        this.generateMultiBtn && (this.generateMultiBtn.disabled = false);
    }

    setCopyEnabled(enabled) {
        if (!this.copyBtn) return;
        this.copyBtn.disabled = !enabled;
        this.copyBtn.classList.toggle('opacity-50', !enabled);
        this.copyBtn.classList.toggle('cursor-not-allowed', !enabled);
    }

    generatePassword(charset, length) {
        // Garante pelo menos 1 char de cada categoria selecionada, depois
        // completa o resto a partir do charset combinado e embaralha tudo.
        const categories = this.getActiveCategories();
        if (categories.length === 0 || length === 0) return '';
        if (length < categories.length) {
            return this.randomFromCharset(charset, length);
        }

        const chars = categories.map(cat => this.pickRandom(cat));
        const remaining = length - chars.length;
        for (const c of this.randomFromCharset(charset, remaining)) {
            chars.push(c);
        }
        return this.shuffle(chars).join('');
    }

    randomFromCharset(charset, count) {
        if (count <= 0) return '';
        const array = new Uint32Array(count);
        crypto.getRandomValues(array);
        return Array.from(array, n => charset[n % charset.length]).join('');
    }

    pickRandom(charset) {
        const array = new Uint32Array(1);
        crypto.getRandomValues(array);
        return charset[array[0] % charset.length];
    }

    // Fisher-Yates com aleatoriedade criptográfica.
    shuffle(arr) {
        const a = arr.slice();
        const rand = new Uint32Array(a.length);
        crypto.getRandomValues(rand);
        for (let i = a.length - 1; i > 0; i--) {
            const j = rand[i] % (i + 1);
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    updateStrength(password) {
        if (!password) {
            this.strengthBar.style.width = '0%';
            this.strengthLabel.textContent = '—';
            return;
        }

        const entropy = this.calculateEntropy(password);
        let level, color, width;

        if (entropy < 40) {
            level = 'Fraca';
            color = 'bg-red-500';
            width = '25%';
        } else if (entropy < 60) {
            level = 'Razoável';
            color = 'bg-orange-500';
            width = '50%';
        } else if (entropy < 80) {
            level = 'Forte';
            color = 'bg-yellow-500';
            width = '75%';
        } else {
            level = 'Muito Forte';
            color = 'bg-emerald-500';
            width = '100%';
        }

        this.strengthBar.className = `h-full rounded-full transition-all duration-300 ${color}`;
        this.strengthBar.style.width = width;
        this.strengthLabel.textContent = `${level} (${Math.round(entropy)} bits)`;
        this.strengthLabel.className = `text-xs font-medium ${color.replace('bg-', 'text-')}`;
    }

    calculateEntropy(password) {
        let poolSize = 0;
        if (/[a-z]/.test(password)) poolSize += 26;
        if (/[A-Z]/.test(password)) poolSize += 26;
        if (/[0-9]/.test(password)) poolSize += 10;
        if (/[^a-zA-Z0-9]/.test(password)) poolSize += 32;
        return password.length * Math.log2(poolSize || 1);
    }

    generateMultiple() {
        const charset = this.getCharset();
        if (!charset) return;

        const count = parseInt(this.multiCount.value) || 5;
        const length = parseInt(this.lengthSlider.value) || 16;

        const passwords = Array.from({ length: count }, () => this.generatePassword(charset, length));

        this.multiList.innerHTML = passwords.map((pwd, i) =>
            `<div class="flex items-center gap-2 p-2 rounded-lg bg-neutral-900/50 border border-neutral-700/50 group">
                <span class="text-xs text-gray-500 w-6">${i + 1}.</span>
                <span class="flex-1 font-mono text-sm text-gray-300 truncate select-all">${escapeHtml(pwd)}</span>
                <button class="copy-single opacity-0 group-hover:opacity-100 py-1 px-2 text-xs text-gray-400 hover:text-white rounded border border-neutral-600 hover:bg-neutral-700 transition-all" data-pwd="${escapeAttr(pwd)}">
                    Copiar
                </button>
            </div>`
        ).join('');

        this.multiList.classList.remove('hidden');

        this.multiList.querySelectorAll('.copy-single').forEach(btn => {
            btn.addEventListener('click', () => this.copy(btn.dataset.pwd));
        });
    }

    async copy(text) {
        if (!text) return;
        try {
            await copyText(text);
            showToast('Copiado!');
        } catch (e) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', e);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new PasswordGenerator();
});
