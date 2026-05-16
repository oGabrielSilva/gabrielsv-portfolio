import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

// Brazilian mobile numbers must include the leading 9 after the DDD,
// so a full BR phone is 10 or 11 digits (DDD + 8 or 9 digit number).
// Generic floor for international numbers is 8 digits.
const MIN_NATIONAL_DIGITS = 8;
const MAX_NATIONAL_DIGITS = 15;

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
        if (national.length < MIN_NATIONAL_DIGITS || national.length > MAX_NATIONAL_DIGITS) {
            return { error: `Número deve ter entre ${MIN_NATIONAL_DIGITS} e ${MAX_NATIONAL_DIGITS} dígitos.` };
        }
        // Brazilian mobiles: 11 digits (2 DDD + 9 number). If DDI is 55 and
        // we only got 10 digits, the leading 9 is probably missing.
        if (ddi === '55' && national.length === 10) {
            return {
                ok: true,
                warn: 'Celulares brasileiros precisam do 9 depois do DDD. Verifique se este é um fixo.',
                phone: ddi + national,
            };
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
