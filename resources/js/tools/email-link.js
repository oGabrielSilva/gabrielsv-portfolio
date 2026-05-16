import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

// RFC 5322-lite check. Tighter than strict spec but covers everything
// a real user will paste; the goal here is to catch typos, not to
// reject valid-but-weird addresses.
const EMAIL_RE = /^[^\s@,;]+@[^\s@,;]+\.[^\s@,;]+$/;

function splitAddresses(raw) {
    if (!raw) return [];
    return raw
        .split(/[,;]+/)
        .map(s => s.trim())
        .filter(Boolean);
}

function invalidIn(list) {
    return list.filter(addr => !EMAIL_RE.test(addr));
}

class EmailLink {
    constructor(root) {
        this.root = root;
        this.toInput = document.getElementById('em-to');
        this.ccInput = document.getElementById('em-cc');
        this.bccInput = document.getElementById('em-bcc');
        this.subjectInput = document.getElementById('em-subject');
        this.bodyInput = document.getElementById('em-body');
        this.output = document.getElementById('em-output');
        this.htmlOutput = document.getElementById('em-html');
        this.openLink = document.getElementById('em-open');
        this.copyBtn = document.getElementById('em-copy');
        this.status = document.getElementById('em-status');

        [this.toInput, this.ccInput, this.bccInput, this.subjectInput, this.bodyInput].forEach(el =>
            el.addEventListener('input', () => this.render())
        );
        this.copyBtn.addEventListener('click', () => this.copyLink());

        this.render();
    }

    render() {
        const to = splitAddresses(this.toInput.value);
        const cc = splitAddresses(this.ccInput.value);
        const bcc = splitAddresses(this.bccInput.value);
        const subject = this.subjectInput.value;
        const body = this.bodyInput.value;

        const allBad = [...invalidIn(to), ...invalidIn(cc), ...invalidIn(bcc)];
        if (to.length === 0 && cc.length === 0 && bcc.length === 0) {
            this.showStatus('error', 'Informe pelo menos um destinatário.');
            this.clear();
            return;
        }
        if (allBad.length > 0) {
            this.showStatus('error', `Endereço inválido: ${allBad[0]}`);
            this.clear();
            return;
        }

        this.showStatus(null);

        const toJoined = to.join(',');
        const params = new URLSearchParams();
        if (cc.length) params.set('cc', cc.join(','));
        if (bcc.length) params.set('bcc', bcc.join(','));
        if (subject) params.set('subject', subject);
        if (body) params.set('body', body);

        // URLSearchParams uses + for spaces, but mailto: clients want %20.
        // Replace literal + with %20 outside of the values themselves.
        const queryString = params.toString().replace(/\+/g, '%20');
        const url = `mailto:${toJoined}${queryString ? `?${queryString}` : ''}`;

        this.output.textContent = url;
        this.htmlOutput.textContent = `<a href="${url}">Enviar e-mail</a>`;
        this.openLink.href = url;
        this.openLink.classList.remove('opacity-50', 'pointer-events-none');
    }

    clear() {
        this.output.textContent = '—';
        this.htmlOutput.textContent = '—';
        this.openLink.removeAttribute('href');
        this.openLink.classList.add('opacity-50', 'pointer-events-none');
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
            showToast('Preencha pelo menos um destinatário válido', { variant: 'error' });
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
    const root = document.querySelector('[data-tool="email-link"]');
    if (root) new EmailLink(root);
});
