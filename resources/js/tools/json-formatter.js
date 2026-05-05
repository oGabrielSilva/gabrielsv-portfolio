class JsonFormatter {
    constructor() {
        this.jsonInput = document.getElementById('json-input');
        this.formatBtn = document.getElementById('format-btn');
        this.minifyBtn = document.getElementById('minify-btn');
        this.validateBtn = document.getElementById('validate-btn');
        this.copyBtn = document.getElementById('copy-btn');
        this.clearBtn = document.getElementById('clear-btn');
        this.indentSize = document.getElementById('indent-size');
        this.statusBar = document.getElementById('status-bar');
        this.statusIcon = document.getElementById('status-icon');
        this.statusText = document.getElementById('status-text');
        this.statKeys = document.getElementById('stat-keys');
        this.statArrays = document.getElementById('stat-arrays');
        this.statSize = document.getElementById('stat-size');
        this.toast = document.getElementById('toast');

        this.init();
    }

    init() {
        this.formatBtn?.addEventListener('click', () => this.format());
        this.minifyBtn?.addEventListener('click', () => this.minify());
        this.validateBtn?.addEventListener('click', () => this.validate());
        this.copyBtn?.addEventListener('click', () => this.copy());
        this.clearBtn?.addEventListener('click', () => this.clear());
        this.jsonInput?.addEventListener('input', () => this.updateStats());
    }

    getIndent() {
        const val = this.indentSize.value;
        if (val === 'tab') return '\t';
        return parseInt(val);
    }

    format() {
        const raw = this.jsonInput.value.trim();
        if (!raw) return this.showStatus('error', 'Cole um JSON para formatar');

        try {
            const parsed = JSON.parse(raw);
            this.jsonInput.value = JSON.stringify(parsed, null, this.getIndent());
            this.showStatus('success', 'JSON formatado com sucesso!');
            this.updateStats();
        } catch (e) {
            this.showStatus('error', this.parseError(e));
        }
    }

    minify() {
        const raw = this.jsonInput.value.trim();
        if (!raw) return this.showStatus('error', 'Cole um JSON para minificar');

        try {
            const parsed = JSON.parse(raw);
            this.jsonInput.value = JSON.stringify(parsed);
            this.showStatus('success', 'JSON minificado com sucesso!');
            this.updateStats();
        } catch (e) {
            this.showStatus('error', this.parseError(e));
        }
    }

    validate() {
        const raw = this.jsonInput.value.trim();
        if (!raw) return this.showStatus('error', 'Cole um JSON para validar');

        try {
            JSON.parse(raw);
            this.showStatus('success', 'JSON válido! ✓');
        } catch (e) {
            this.showStatus('error', this.parseError(e));
        }
    }

    parseError(e) {
        const msg = e.message;
        const posMatch = msg.match(/position (\d+)/i);
        if (posMatch) {
            const pos = parseInt(posMatch[1]);
            const text = this.jsonInput.value.substring(0, pos);
            const line = (text.match(/\n/g) || []).length + 1;
            const col = pos - text.lastIndexOf('\n');
            return `Erro na linha ${line}, coluna ${col}: ${msg}`;
        }
        return `JSON inválido: ${msg}`;
    }

    showStatus(type, message) {
        this.statusBar.classList.remove('hidden', 'bg-bulma-primary/10', 'text-bulma-primary', 'bg-red-500/10', 'text-red-400');

        if (type === 'success') {
            this.statusBar.classList.add('bg-bulma-primary/10', 'text-bulma-primary');
        } else {
            this.statusBar.classList.add('bg-red-500/10', 'text-red-400');
        }

        this.statusText.textContent = message;
    }

    updateStats() {
        const raw = this.jsonInput.value;
        const size = new Blob([raw]).size;
        this.statSize.textContent = this.formatBytes(size);

        try {
            const parsed = JSON.parse(raw);
            const stats = this.countStats(parsed);
            this.statKeys.textContent = stats.keys + ' chaves';
            this.statArrays.textContent = stats.arrays + ' arrays';
        } catch {
            this.statKeys.textContent = '— chaves';
            this.statArrays.textContent = '— arrays';
        }
    }

    countStats(obj) {
        let keys = 0;
        let arrays = 0;

        const walk = (val) => {
            if (Array.isArray(val)) {
                arrays++;
                val.forEach(walk);
            } else if (val && typeof val === 'object') {
                const objKeys = Object.keys(val);
                keys += objKeys.length;
                objKeys.forEach(k => walk(val[k]));
            }
        };

        walk(obj);
        return { keys, arrays };
    }

    formatBytes(bytes) {
        if (bytes === 0) return '0 bytes';
        if (bytes < 1024) return bytes + ' bytes';
        return (bytes / 1024).toFixed(1) + ' KB';
    }

    async copy() {
        const text = this.jsonInput.value;
        if (!text) return;

        try {
            await navigator.clipboard.writeText(text);
            this.showToast();
        } catch (e) {
            console.error('Erro ao copiar:', e);
        }
    }

    clear() {
        this.jsonInput.value = '';
        this.statusBar.classList.add('hidden');
        this.updateStats();
    }

    showToast() {
        this.toast.classList.remove('translate-y-2', 'opacity-0');
        this.toast.classList.add('translate-y-0', 'opacity-100');
        setTimeout(() => {
            this.toast.classList.remove('translate-y-0', 'opacity-100');
            this.toast.classList.add('translate-y-2', 'opacity-0');
        }, 2000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new JsonFormatter();
});
