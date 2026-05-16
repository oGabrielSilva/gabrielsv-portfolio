import { marked } from 'marked';
import DOMPurify from 'dompurify';

DOMPurify.addHook('afterSanitizeAttributes', (node) => {
    if (node.tagName === 'A' && node.getAttribute('target') === '_blank') {
        node.setAttribute('rel', 'noopener noreferrer');
    }
});

const PURIFY_CONFIG = {
    USE_PROFILES: { html: true },
    ADD_ATTR: ['target', 'rel'],
};

class MarkdownPreview {
    constructor() {
        this.mdInput = document.getElementById('md-input');
        this.mdPreview = document.getElementById('md-preview');
        this.charCount = document.getElementById('char-count');
        this.copyMdBtn = document.getElementById('copy-md-btn');
        this.copyHtmlBtn = document.getElementById('copy-html-btn');
        this.exampleBtn = document.getElementById('example-btn');
        this.toast = document.getElementById('toast');
        this.toastText = document.getElementById('toast-text');

        this.debounceTimer = null;

        // Configure marked
        marked.setOptions({
            breaks: true,
            gfm: true,
        });

        this.init();
    }

    init() {
        this.mdInput?.addEventListener('input', () => {
            this.updateCharCount();
            this.debounceRender();
        });

        this.copyMdBtn?.addEventListener('click', () => this.copyMarkdown());
        this.copyHtmlBtn?.addEventListener('click', () => this.copyHtml());
        this.exampleBtn?.addEventListener('click', () => this.loadExample());
    }

    debounceRender() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.render(), 150);
    }

    render() {
        const md = this.mdInput.value;
        if (!md.trim()) {
            this.mdPreview.innerHTML = '<p class="text-gray-500 italic">O preview aparecerá aqui...</p>';
            return;
        }

        try {
            const dirty = marked.parse(md);
            this.mdPreview.innerHTML = DOMPurify.sanitize(dirty, PURIFY_CONFIG);
        } catch (e) {
            this.mdPreview.textContent = `Erro ao renderizar: ${e.message}`;
        }
    }

    updateCharCount() {
        const count = this.mdInput.value.length;
        this.charCount.textContent = count + ' caracteres';
    }

    async copyMarkdown() {
        const text = this.mdInput.value;
        if (!text) return;

        try {
            await navigator.clipboard.writeText(text);
            this.showToast('Markdown copiado!');
        } catch (e) {
            console.error('Erro ao copiar:', e);
        }
    }

    async copyHtml() {
        const md = this.mdInput.value;
        if (!md) return;

        try {
            const html = DOMPurify.sanitize(marked.parse(md), PURIFY_CONFIG);
            await navigator.clipboard.writeText(html);
            this.showToast('HTML copiado!');
        } catch (e) {
            console.error('Erro ao copiar:', e);
        }
    }

    loadExample() {
        this.mdInput.value = `# Título Principal

## Subtítulo

Parágrafo com **negrito**, *itálico* e \`código inline\`.

### Lista não ordenada

- Item 1
- Item 2
  - Sub-item 2.1
  - Sub-item 2.2
- Item 3

### Lista ordenada

1. Primeiro
2. Segundo
3. Terceiro

### Link e Imagem

[Visite o Google](https://google.com)

### Citação

> "A simplicidade é o último grau de sofisticação." — Leonardo da Vinci

### Código

\`\`\`javascript
function hello(name) {
    console.log(\`Olá, \${name}!\`);
}
\`\`\`

### Tabela

| Nome | Idade | Cidade |
|------|-------|--------|
| Ana  | 28    | SP     |
| João | 32    | RJ     |
| Maria| 25    | BH     |

### Checklist

- [x] Tarefa concluída
- [ ] Tarefa pendente
- [ ] Outra tarefa

---

*Fim do exemplo*`;
        this.updateCharCount();
        this.render();
    }

    showToast(message) {
        this.toastText.textContent = message;
        this.toast.classList.remove('translate-y-2', 'opacity-0');
        this.toast.classList.add('translate-y-0', 'opacity-100');
        setTimeout(() => {
            this.toast.classList.remove('translate-y-0', 'opacity-100');
            this.toast.classList.add('translate-y-2', 'opacity-0');
        }, 2000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new MarkdownPreview();
});
