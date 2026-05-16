import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

class RemoveDuplicates {
    constructor(root) {
        this.root = root;
        this.input = document.getElementById('dup-input');
        this.output = document.getElementById('dup-output');
        this.statOriginal = document.getElementById('stat-original');
        this.statResult = document.getElementById('stat-result');
        this.statRemoved = document.getElementById('stat-removed');
        this.copyBtn = document.getElementById('copy-btn');

        this.optIgnoreCase = document.getElementById('opt-ignore-case');
        this.optTrim = document.getElementById('opt-trim');
        this.optSkipEmpty = document.getElementById('opt-skip-empty');
        this.optSort = document.getElementById('opt-sort');

        const inputs = [this.input, this.optIgnoreCase, this.optTrim, this.optSkipEmpty, this.optSort];
        inputs.forEach(el => el.addEventListener('input', () => this.run()));
        this.copyBtn.addEventListener('click', () => this.copy());

        this.run();
    }

    run() {
        const raw = this.input.value;
        const lines = raw.split('\n');
        const seen = new Set();
        const kept = [];

        for (const line of lines) {
            const normalizedForCompare = this.normalize(line);
            if (this.optSkipEmpty.checked && normalizedForCompare === '') continue;
            const key = this.optIgnoreCase.checked
                ? normalizedForCompare.toLowerCase()
                : normalizedForCompare;
            if (seen.has(key)) continue;
            seen.add(key);
            kept.push(this.optTrim.checked ? normalizedForCompare : line);
        }

        if (this.optSort.checked) kept.sort((a, b) => a.localeCompare(b, 'pt-BR'));

        this.output.value = kept.join('\n');
        const original = lines.length;
        const result = kept.length;
        const removed = original - result;
        this.statOriginal.textContent = original;
        this.statResult.textContent = result;
        this.statRemoved.textContent = `${removed} removida${removed === 1 ? '' : 's'}`;
    }

    normalize(line) {
        return this.optTrim.checked ? line.trim().replace(/\s+/g, ' ') : line;
    }

    async copy() {
        if (!this.output.value) {
            showToast('Nada para copiar', { variant: 'error' });
            return;
        }
        try {
            await copyText(this.output.value);
            showToast('Copiado!');
        } catch (err) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', err);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="remove-duplicates"]');
    if (root) new RemoveDuplicates(root);
});
