import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

// E.164: o número internacional inteiro (DDI + número nacional) tem no
// máximo 15 dígitos, sem o sinal de +. O mínimo prático para conseguir
// rotear é 8 dígitos no total. DDI começa com 1-9 (sem zero à esquerda).
const E164_MIN_TOTAL = 8;
const E164_MAX_TOTAL = 15;
const DDI_RE = /^[1-9]\d{0,2}$/;

class WhatsappLink {
    constructor(root) {
        this.root = root;
        this.ddiInput = document.getElementById('wa-ddi');
        this.phoneInput = document.getElementById('wa-phone');
        this.messageInput = document.getElementById('wa-message');
        this.formatRadios = root.querySelectorAll('input[name="wa-format"]');
        this.output = document.getElementById('wa-output');
        this.htmlOutput = document.getElementById('wa-html');
        this.openLink = document.getElementById('wa-open');
        this.copyBtn = document.getElementById('wa-copy');
        this.status = document.getElementById('wa-status');

        [this.ddiInput, this.phoneInput, this.messageInput].forEach(el =>
            el.addEventListener('input', () => this.render())
        );
        this.formatRadios.forEach(r => r.addEventListener('change', () => this.render()));
        this.copyBtn.addEventListener('click', () => this.copyLink());

        this.render();
    }

    digitsOnly(value) {
        return (value || '').replace(/\D+/g, '');
    }

    selectedFormat() {
        const checked = Array.from(this.formatRadios).find(r => r.checked);
        return checked ? checked.value : 'wa.me';
    }

    buildLink() {
        const ddi = this.digitsOnly(this.ddiInput.value);
        const national = this.digitsOnly(this.phoneInput.value);
        if (!ddi || !national) {
            return { error: 'Informe DDI e número.' };
        }
        if (!DDI_RE.test(ddi)) {
            return { error: 'DDI inválido. Use 1 a 3 dígitos sem zero à esquerda (ex: 55, 1, 351).' };
        }

        const total = ddi.length + national.length;
        if (total < E164_MIN_TOTAL || total > E164_MAX_TOTAL) {
            return { error: `O número completo (DDI + número) deve ter entre ${E164_MIN_TOTAL} e ${E164_MAX_TOTAL} dígitos. Você tem ${total}.` };
        }

        // Regras específicas do Brasil (DDI 55): DDD com 2 dígitos (11-99) e
        // celular precisa do 9 depois do DDD. Apenas avisamos, não bloqueamos,
        // porque o usuário pode estar gerando link para um fixo legítimo.
        if (ddi === '55') {
            if (national.length < 10) {
                return { error: 'Números brasileiros precisam de DDD (2 dígitos) + número (8 ou 9 dígitos).' };
            }
            const ddd = parseInt(national.slice(0, 2), 10);
            if (ddd < 11 || ddd > 99) {
                return { error: 'DDD brasileiro inválido (use 11-99).' };
            }
            if (national.length === 10) {
                return {
                    ok: true,
                    warn: 'Celulares brasileiros precisam do 9 depois do DDD. Verifique se este é um fixo.',
                    phone: ddi + national,
                };
            }
        }

        return { ok: true, phone: ddi + national };
    }

    render() {
        const result = this.buildLink();
        const message = this.messageInput.value;

        if (result.error) {
            this.showStatus('error', result.error);
            this.output.textContent = '—';
            this.htmlOutput.textContent = '—';
            this.openLink.classList.add('opacity-50', 'pointer-events-none');
            this.openLink.removeAttribute('href');
            return;
        }

        if (result.warn) {
            this.showStatus('warn', result.warn);
        } else {
            this.showStatus(null);
        }

        const params = message ? `?text=${encodeURIComponent(message)}` : '';
        const url = this.selectedFormat() === 'api'
            ? `https://api.whatsapp.com/send?phone=${result.phone}${message ? `&text=${encodeURIComponent(message)}` : ''}`
            : `https://wa.me/${result.phone}${params}`;

        this.output.textContent = url;
        this.htmlOutput.textContent = `<a href="${url}" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a>`;

        this.openLink.href = url;
        this.openLink.classList.remove('opacity-50', 'pointer-events-none');
    }

    showStatus(kind, message = '') {
        if (!kind) {
            this.status.classList.add('hidden');
            return;
        }
        this.status.classList.remove(
            'hidden',
            'bg-red-500/10', 'text-red-400',
            'bg-amber-500/10', 'text-amber-400',
        );
        if (kind === 'error') {
            this.status.classList.add('bg-red-500/10', 'text-red-400');
        } else {
            this.status.classList.add('bg-amber-500/10', 'text-amber-400');
        }
        this.status.textContent = message;
    }

    async copyLink() {
        const url = this.output.textContent.trim();
        if (!url || url === '—') {
            showToast('Preencha o número antes de copiar', { variant: 'error' });
            return;
        }
        try {
            await copyText(url);
            showToast('Link copiado!');
        } catch (err) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', err);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="whatsapp-link"]');
    if (root) new WhatsappLink(root);
});
