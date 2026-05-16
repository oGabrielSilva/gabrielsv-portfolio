import { escapeHtml } from '../utils/dom.js';
import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';
import { postJson, ApiError } from '../utils/api.js';

class LoremGenerator {
    constructor(root) {
        this.root = root;
        this.generateUrl = root.dataset.generateUrl;
        this.type = root.dataset.initialType || 'paragraphs';
        this.text = [];
        this.loading = false;

        this.typeButtons = document.querySelectorAll('[data-lorem-type]');
        this.quantityInput = document.getElementById('quantity');
        this.startWithLoremCheckbox = document.getElementById('start-with-lorem');
        this.generateBtn = document.getElementById('generate-btn');
        this.generateIcon = document.getElementById('generate-icon');
        this.generateText = document.getElementById('generate-text');
        this.resultContainer = document.getElementById('result-container');
        this.textResult = document.getElementById('text-result');
        this.wordCount = document.getElementById('word-count');
        this.copyBtn = document.getElementById('copy-btn');

        this.init();
    }

    init() {
        this.typeButtons?.forEach(btn => {
            btn.addEventListener('click', () => {
                this.type = btn.dataset.loremType;
                this.updateTypeButtons();
                const dropdown = document.querySelector('#lorem-type-dropdown');
                if (dropdown && window.HSDropdown) window.HSDropdown.close(dropdown);
            });
        });

        this.generateBtn?.addEventListener('click', () => this.generate());
        this.copyBtn?.addEventListener('click', () => this.copy());

        window.HSStaticMethods?.autoInit();
        this.updateTypeButtons();
    }

    updateTypeButtons() {
        this.typeButtons?.forEach(btn => {
            const active = btn.dataset.loremType === this.type;
            btn.classList.toggle('bg-bulma-primary/10', active);
            btn.classList.toggle('text-bulma-primary', active);
            btn.classList.toggle('text-gray-300', !active);
        });

        const dropdownLabel = document.getElementById('lorem-type-label');
        const activeButton = Array.from(this.typeButtons).find(btn => btn.dataset.loremType === this.type);
        if (dropdownLabel && activeButton) {
            dropdownLabel.textContent = activeButton.querySelector('span').textContent;
        }
    }

    async generate() {
        if (this.loading) return;
        this.setLoading(true);

        try {
            const data = await postJson(this.generateUrl, {
                type: this.type,
                quantity: parseInt(this.quantityInput.value, 10) || 3,
                start_with_lorem: this.startWithLoremCheckbox.checked,
            });
            this.text = data.text;
            this.renderText();
        } catch (error) {
            const msg = error instanceof ApiError && error.status === 419
                ? 'Sessão expirada, recarregue a página'
                : 'Erro ao gerar Lorem Ipsum';
            showToast(msg, { variant: 'error' });
            console.error('Erro ao gerar Lorem Ipsum:', error);
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(loading) {
        this.loading = loading;
        this.generateBtn.disabled = loading;
        this.generateIcon.classList.toggle('animate-spin', loading);
        this.generateText.textContent = loading ? 'Gerando...' : 'Gerar';
    }

    renderText() {
        if (this.type === 'paragraphs') {
            this.textResult.innerHTML = this.text.map(p =>
                `<p class="mb-4 text-gray-300 leading-relaxed">${escapeHtml(p)}</p>`
            ).join('');
        } else if (this.type === 'sentences') {
            this.textResult.innerHTML = `<p class="text-gray-300 leading-relaxed">${this.text.map(s => escapeHtml(s)).join(' ')}</p>`;
        } else {
            this.textResult.innerHTML = `<p class="text-gray-300 leading-relaxed">${escapeHtml(this.text[0])}</p>`;
        }

        const fullText = Array.isArray(this.text) ? this.text.join(' ') : this.text;
        const count = fullText.split(/\s+/).filter(Boolean).length;
        this.wordCount.textContent = count + ' palavras';

        this.resultContainer.classList.remove('hidden');
    }

    async copy() {
        // Prefer the in-memory text (set after a fresh generate() call).
        // If empty (user lands on the page and copies the SSR-rendered
        // text without regenerating), fall back to scraping the result
        // container so we never copy an empty string.
        let fullText = '';
        if (Array.isArray(this.text) && this.text.length > 0) {
            fullText = this.text.join('\n\n');
        } else if (typeof this.text === 'string' && this.text.length > 0) {
            fullText = this.text;
        } else if (this.textResult) {
            const paragraphs = this.textResult.querySelectorAll('p');
            fullText = paragraphs.length
                ? Array.from(paragraphs).map(p => p.textContent.trim()).join('\n\n')
                : this.textResult.textContent.trim();
        }

        if (!fullText) {
            showToast('Nada para copiar', { variant: 'error' });
            return;
        }

        try {
            await copyText(fullText);
            showToast('Copiado!');
        } catch (error) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="lorem"]');
    if (root) new LoremGenerator(root);
});
