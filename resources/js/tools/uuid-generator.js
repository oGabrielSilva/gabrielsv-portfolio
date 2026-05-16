import { escapeHtml, escapeAttr } from '../utils/dom.js';
import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';
import { postJson, ApiError } from '../utils/api.js';
import { refreshIcons } from '../utils/lucide.js';

class UuidGenerator {
    constructor(root) {
        this.root = root;
        this.generateUrl = root.dataset.generateUrl;
        this.currentType = root.dataset.currentType;
        this.loading = false;

        this.quantityInput = document.getElementById('quantity');
        this.generateBtn = document.getElementById('generate-btn');
        this.generateIcon = document.getElementById('generate-icon');
        this.generateText = document.getElementById('generate-text');
        this.idsList = document.getElementById('ids-list');
        this.copyAllBtn = document.getElementById('copy-all-btn');

        this.init();
    }

    init() {
        this.generateBtn?.addEventListener('click', () => this.generate());
        this.copyAllBtn?.addEventListener('click', () => this.copyAll());

        this.idsList?.addEventListener('click', (e) => {
            const copyBtn = e.target.closest('.copy-btn');
            if (copyBtn) this.copy(copyBtn.dataset.value);
        });

        window.HSStaticMethods?.autoInit();
    }

    async generate() {
        if (this.loading) return;
        this.setLoading(true);

        try {
            const data = await postJson(this.generateUrl, {
                type: this.currentType,
                quantity: parseInt(this.quantityInput.value, 10) || 5,
            });
            this.ids = data.ids;
            this.renderIds();
        } catch (error) {
            const msg = error instanceof ApiError && error.status === 419
                ? 'Sessão expirada, recarregue a página'
                : 'Erro ao gerar UUIDs';
            showToast(msg, { variant: 'error' });
            console.error('Erro ao gerar UUIDs:', error);
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

    renderIds() {
        this.idsList.innerHTML = this.ids.map(id => `
            <div class="flex items-center gap-2 group">
                <code class="flex-1 py-2 px-3 bg-neutral-900 rounded-lg text-xs sm:text-sm text-gray-300 font-mono break-all uuid-item">${escapeHtml(id)}</code>
                <button type="button" class="copy-btn p-2 text-gray-500 hover:text-bulma-primary transition-colors" data-value="${escapeAttr(id)}" title="Copiar">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>
        `).join('');

        refreshIcons(this.idsList);
    }

    async copy(text) {
        try {
            await copyText(text);
            showToast('Copiado!');
        } catch (error) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', error);
        }
    }

    async copyAll() {
        const items = this.idsList.querySelectorAll('.uuid-item');
        const values = Array.from(items).map(item => item.textContent);

        try {
            await copyText(values.join('\n'));
            showToast('Todos copiados!');
        } catch (error) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="uuid"]');
    if (root) new UuidGenerator(root);
});
