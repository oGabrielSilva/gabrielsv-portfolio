import { escapeHtml } from '../utils/dom.js';

class UrlValidator {
    constructor(root) {
        this.root = root;
        this.input = document.getElementById('url-input');
        this.status = document.getElementById('status');
        this.partsList = document.getElementById('parts-list');
        this.queryList = document.getElementById('query-list');
        this.warnings = document.getElementById('warnings');

        this.input.addEventListener('input', () => this.run());
        this.run();
    }

    run() {
        const raw = this.input.value.trim();
        if (!raw) {
            this.showStatus(null);
            this.clearParts();
            this.queryList.textContent = 'Nenhum parâmetro.';
            this.warnings.innerHTML = '';
            return;
        }

        let url;
        try {
            url = new URL(raw);
        } catch {
            this.showStatus('error', 'URL inválida. Inclua um protocolo (http://, https://) se necessário.');
            this.clearParts();
            this.queryList.textContent = 'Nenhum parâmetro.';
            this.warnings.innerHTML = '';
            return;
        }

        this.showStatus('success', 'URL válida.');
        this.fillParts(url);
        this.fillQuery(url);
        this.fillWarnings(url);
    }

    showStatus(kind, message = '') {
        if (!kind) {
            this.status.classList.add('hidden');
            return;
        }
        this.status.classList.remove('hidden', 'bg-emerald-500/10', 'text-emerald-400', 'bg-red-500/10', 'text-red-400');
        if (kind === 'success') {
            this.status.classList.add('bg-emerald-500/10', 'text-emerald-400');
        } else {
            this.status.classList.add('bg-red-500/10', 'text-red-400');
        }
        this.status.textContent = message;
    }

    fillParts(url) {
        const map = {
            protocol: url.protocol,
            host: url.host,
            hostname: url.hostname,
            port: url.port,
            pathname: url.pathname,
            search: url.search,
            hash: url.hash,
            username: url.username,
            password: url.password ? '•'.repeat(url.password.length) : '',
        };
        this.partsList.querySelectorAll('[data-part]').forEach(row => {
            const val = map[row.dataset.part];
            row.querySelector('dd').textContent = val || '—';
        });
    }

    clearParts() {
        this.partsList.querySelectorAll('dd').forEach(dd => dd.textContent = '—');
    }

    fillQuery(url) {
        const params = Array.from(url.searchParams.entries());
        if (params.length === 0) {
            this.queryList.textContent = 'Nenhum parâmetro.';
            return;
        }
        this.queryList.innerHTML = params.map(([k, v]) =>
            `<div class="flex gap-3 py-1 border-b border-neutral-700/30 last:border-0">
                <span class="w-32 shrink-0 text-bulma-primary font-mono">${escapeHtml(k)}</span>
                <span class="flex-1 font-mono text-gray-300 break-all">${escapeHtml(v)}</span>
            </div>`
        ).join('');
    }

    fillWarnings(url) {
        const items = [];

        if (url.protocol === 'http:') {
            items.push({ tone: 'warn', text: 'Protocolo inseguro: prefira https em produção.' });
        }
        if (url.protocol === 'javascript:') {
            items.push({ tone: 'error', text: 'javascript: URLs são frequentemente sinal de XSS — nunca renderize sem sanitizar.' });
        }
        if (url.username || url.password) {
            items.push({ tone: 'warn', text: 'Credenciais embutidas na URL são depreciadas e podem ser logadas por proxies.' });
        }
        if (url.port && !['80', '443', ''].includes(url.port)) {
            items.push({ tone: 'info', text: `Usando porta não padrão: ${url.port}.` });
        }
        if (/[^\x00-\x7F]/.test(url.hostname)) {
            const idn = url.hostname;
            items.push({ tone: 'info', text: `Hostname contém caracteres Unicode (IDN): ${escapeHtml(idn)}. Considere a forma Punycode para compatibilidade.` });
        }
        if (url.hostname && /^\d+(\.\d+){3}$/.test(url.hostname)) {
            items.push({ tone: 'info', text: 'Hostname é um IPv4 literal — pode prejudicar SEO e SSL automático.' });
        }
        if (url.pathname.length > 1 && /\/\//.test(url.pathname)) {
            items.push({ tone: 'warn', text: 'Path contém barras duplicadas — pode causar 404 em alguns servidores.' });
        }

        if (items.length === 0) {
            this.warnings.innerHTML = '<li class="text-emerald-400">Nenhum problema detectado.</li>';
            return;
        }

        const toneColors = {
            error: 'text-red-400',
            warn: 'text-amber-400',
            info: 'text-sky-400',
        };
        this.warnings.innerHTML = items.map(item =>
            `<li class="${toneColors[item.tone]}">• ${item.text}</li>`
        ).join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="url-validator"]');
    if (root) new UrlValidator(root);
});
