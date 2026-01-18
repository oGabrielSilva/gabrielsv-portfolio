class UuidGenerator {
    constructor() {
        this.ids = [];
        this.loading = false;

        this.quantityInput = document.getElementById('quantity');
        this.generateBtn = document.getElementById('generate-btn');
        this.generateIcon = document.getElementById('generate-icon');
        this.generateText = document.getElementById('generate-text');
        this.idsList = document.getElementById('ids-list');
        this.copyAllBtn = document.getElementById('copy-all-btn');
        this.toast = document.getElementById('toast');

        this.init();
    }

    init() {
        this.generateBtn?.addEventListener('click', () => this.generate());
        this.copyAllBtn?.addEventListener('click', () => this.copyAll());

        // Delegação de eventos para os botões de cópia
        this.idsList?.addEventListener('click', (e) => {
            const copyBtn = e.target.closest('.copy-btn');
            if (copyBtn) {
                this.copy(copyBtn.dataset.value);
            }
        });

        // Inicializa o Preline
        window.HSStaticMethods?.autoInit();
    }

    async generate() {
        if (this.loading) return;

        this.setLoading(true);

        try {
            const response = await fetch(window.uuidConfig.generateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.uuidConfig.csrfToken,
                },
                body: JSON.stringify({
                    type: window.uuidConfig.currentType,
                    quantity: parseInt(this.quantityInput.value) || 5,
                }),
            });

            const data = await response.json();
            this.ids = data.ids;
            this.renderIds();
        } catch (error) {
            console.error('Erro ao gerar UUIDs:', error);
        } finally {
            this.setLoading(false);
        }
    }

    setLoading(loading) {
        this.loading = loading;
        this.generateBtn.disabled = loading;

        if (loading) {
            this.generateIcon.classList.add('animate-spin');
            this.generateText.textContent = 'Gerando...';
        } else {
            this.generateIcon.classList.remove('animate-spin');
            this.generateText.textContent = 'Gerar';
        }
    }

    renderIds() {
        this.idsList.innerHTML = this.ids.map(id => `
            <div class="flex items-center gap-2 group">
                <code class="flex-1 py-2 px-3 bg-neutral-900 rounded-lg text-xs sm:text-sm text-gray-300 font-mono break-all uuid-item">${this.escapeHtml(id)}</code>
                <button type="button" class="copy-btn p-2 text-gray-500 hover:text-bulma-primary transition-colors" data-value="${this.escapeHtml(id)}" title="Copiar">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>
        `).join('');

        // Re-init Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async copy(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showToast();
        } catch (error) {
            console.error('Erro ao copiar:', error);
        }
    }

    async copyAll() {
        const items = this.idsList.querySelectorAll('.uuid-item');
        const values = Array.from(items).map(item => item.textContent);

        try {
            await navigator.clipboard.writeText(values.join('\n'));
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
    new UuidGenerator();
});
