import 'preline/preline';

class LoremGenerator {
    constructor() {
        this.text = [];
        this.type = 'paragraphs';
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
        this.toast = document.getElementById('toast');

        this.init();
    }

    init() {
        // Type selection
        this.typeButtons?.forEach(btn => {
            btn.addEventListener('click', () => {
                this.type = btn.dataset.loremType;
                this.updateTypeButtons();
                // Fecha o dropdown do Preline
                const dropdown = document.querySelector('#lorem-type-dropdown');
                if (dropdown && window.HSDropdown) {
                    window.HSDropdown.close(dropdown);
                }
            });
        });

        // Generate button
        this.generateBtn?.addEventListener('click', () => this.generate());

        // Copy button
        this.copyBtn?.addEventListener('click', () => this.copy());

        // Inicializa o Preline
        window.HSStaticMethods?.autoInit();

        // Atualiza botões de tipo inicial
        this.updateTypeButtons();
    }

    updateTypeButtons() {
        this.typeButtons?.forEach(btn => {
            if (btn.dataset.loremType === this.type) {
                btn.classList.add('bg-bulma-primary/10', 'text-bulma-primary');
                btn.classList.remove('text-gray-300');
            } else {
                btn.classList.remove('bg-bulma-primary/10', 'text-bulma-primary');
                btn.classList.add('text-gray-300');
            }
        });

        // Atualiza o label do dropdown
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
            const response = await fetch(window.loremConfig.generateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.loremConfig.csrfToken,
                },
                body: JSON.stringify({
                    type: this.type,
                    quantity: parseInt(this.quantityInput.value) || 3,
                    start_with_lorem: this.startWithLoremCheckbox.checked,
                }),
            });

            const data = await response.json();
            this.text = data.text;
            this.renderText();
        } catch (error) {
            console.error('Erro ao gerar Lorem Ipsum:', error);
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(loading) {
        this.loading = loading;
        this.generateBtn.disabled = loading;

        if (loading) {
            this.generateIcon.className = 'fa-solid fa-spinner fa-spin';
            this.generateText.textContent = 'Gerando...';
        } else {
            this.generateIcon.className = 'fa-solid fa-wand-magic-sparkles';
            this.generateText.textContent = 'Gerar';
        }
    }

    renderText() {
        if (this.type === 'paragraphs') {
            this.textResult.innerHTML = this.text.map(p =>
                `<p class="mb-4 text-gray-300 leading-relaxed">${this.escapeHtml(p)}</p>`
            ).join('');
        } else if (this.type === 'sentences') {
            this.textResult.innerHTML = `<p class="text-gray-300 leading-relaxed">${this.text.map(s => this.escapeHtml(s)).join(' ')}</p>`;
        } else {
            this.textResult.innerHTML = `<p class="text-gray-300 leading-relaxed">${this.escapeHtml(this.text[0])}</p>`;
        }

        // Atualiza contagem de palavras
        const fullText = Array.isArray(this.text) ? this.text.join(' ') : this.text;
        const count = fullText.split(/\s+/).filter(w => w).length;
        this.wordCount.textContent = count + ' palavras';

        // Mostra o resultado
        this.resultContainer.classList.remove('hidden');
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async copy() {
        const fullText = Array.isArray(this.text) ? this.text.join('\n\n') : this.text;

        try {
            await navigator.clipboard.writeText(fullText);
            this.showToast();
        } catch (error) {
            console.error('Erro ao copiar:', error);
        }
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
    new LoremGenerator();
});
