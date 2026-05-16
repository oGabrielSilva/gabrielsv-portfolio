import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

// Split a string into words across spaces, punctuation and case boundaries
// so that "helloWorld_OK 123" becomes ['hello', 'World', 'OK', '123'].
function splitWords(text) {
    return text
        .normalize('NFD').replace(/[̀-ͯ]/g, '') // strip accents
        .replace(/([a-z\d])([A-Z])/g, '$1 $2')            // camelCase boundary
        .replace(/([A-Z]+)([A-Z][a-z])/g, '$1 $2')        // acronym boundary
        .replace(/[^a-zA-Z0-9]+/g, ' ')
        .trim()
        .split(/\s+/)
        .filter(Boolean);
}

const transforms = {
    upper: (t) => t.toUpperCase(),
    lower: (t) => t.toLowerCase(),
    title: (t) => splitWords(t).map(w => w[0].toUpperCase() + w.slice(1).toLowerCase()).join(' '),
    sentence: (t) => {
        const lower = t.toLowerCase();
        return lower.replace(/(^|[.!?]\s+)([a-zà-ÿ])/g, (_, sep, ch) => sep + ch.toUpperCase());
    },
    camel: (t) => {
        const words = splitWords(t);
        return words.map((w, i) =>
            i === 0 ? w.toLowerCase() : w[0].toUpperCase() + w.slice(1).toLowerCase()
        ).join('');
    },
    pascal: (t) => splitWords(t).map(w => w[0].toUpperCase() + w.slice(1).toLowerCase()).join(''),
    snake: (t) => splitWords(t).map(w => w.toLowerCase()).join('_'),
    kebab: (t) => splitWords(t).map(w => w.toLowerCase()).join('-'),
    constant: (t) => splitWords(t).map(w => w.toUpperCase()).join('_'),
};

class CaseConverter {
    constructor(root) {
        this.root = root;
        this.input = document.getElementById('case-input');
        this.rows = root.querySelectorAll('[data-variant]');

        this.input.addEventListener('input', () => this.render());
        this.rows.forEach(row => {
            row.querySelector('.copy-variant').addEventListener('click', () => {
                this.copy(row.querySelector('.case-output').textContent);
            });
        });

        this.render();
    }

    render() {
        const text = this.input.value;
        this.rows.forEach(row => {
            const variant = row.dataset.variant;
            const out = transforms[variant] ? transforms[variant](text) : text;
            row.querySelector('.case-output').textContent = out;
        });
    }

    async copy(text) {
        if (!text) return;
        try {
            await copyText(text);
            showToast('Copiado!');
        } catch (err) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', err);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="case-converter"]');
    if (root) new CaseConverter(root);
});
