<div
    id="command-palette"
    class="fixed inset-0 z-[60] hidden items-start justify-center bg-black/70 px-4 pt-20 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="command-palette-label"
    data-command-palette
>
    <div class="w-full max-w-2xl overflow-hidden rounded-2xl border border-neutral-700 bg-neutral-900 shadow-2xl"
         data-command-palette-panel>
        <div class="flex items-center gap-3 border-b border-neutral-800 px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <label id="command-palette-label" for="command-palette-input" class="sr-only">Buscar posts e ferramentas</label>
            <input
                id="command-palette-input"
                type="search"
                placeholder="Buscar posts, ferramentas, páginas…"
                class="w-full bg-transparent text-base text-white placeholder:text-gray-500 focus:outline-none"
                autocomplete="off"
                spellcheck="false"
                data-command-palette-input
            >
            <kbd class="hidden font-mono text-[10px] text-gray-600 sm:inline">ESC</kbd>
        </div>

        <div class="max-h-96 overflow-y-auto" data-command-palette-results>
            <p class="px-4 py-8 text-center text-sm text-gray-500" data-command-palette-empty>
                Comece a digitar (mín. 2 caracteres)…
            </p>
        </div>

        <div class="flex items-center justify-between gap-3 border-t border-neutral-800 px-4 py-2 text-[11px] text-gray-500">
            <span class="flex items-center gap-2">
                <kbd class="rounded bg-neutral-800 px-1.5 py-0.5 font-mono">↑↓</kbd> navegar
                <kbd class="rounded bg-neutral-800 px-1.5 py-0.5 font-mono">↵</kbd> abrir
            </span>
            <span>busca pelo site</span>
        </div>
    </div>
</div>

<script>
(function () {
    const root = document.querySelector('[data-command-palette]');
    if (!root) return;

    const panel = root.querySelector('[data-command-palette-panel]');
    const input = root.querySelector('[data-command-palette-input]');
    const results = root.querySelector('[data-command-palette-results]');
    const empty = root.querySelector('[data-command-palette-empty]');

    let activeIndex = -1;
    let items = [];
    let abortController = null;
    let debounceTimer = null;

    const open = () => {
        root.classList.remove('hidden');
        root.classList.add('flex');
        setTimeout(() => input.focus(), 30);
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        root.classList.add('hidden');
        root.classList.remove('flex');
        input.value = '';
        results.innerHTML = '';
        results.appendChild(empty);
        empty.textContent = 'Comece a digitar (mín. 2 caracteres)…';
        activeIndex = -1;
        items = [];
        document.body.style.overflow = '';
    };

    const render = (data) => {
        results.innerHTML = '';
        if (!data.length) {
            empty.textContent = 'Nada encontrado.';
            results.appendChild(empty);
            items = [];
            activeIndex = -1;
            return;
        }

        items = data.map((item, i) => {
            const a = document.createElement('a');
            a.href = item.url;
            a.className = 'flex items-center justify-between gap-3 border-b border-neutral-800/60 px-4 py-3 transition-colors hover:bg-neutral-800/60';
            a.dataset.index = i;
            a.innerHTML = `
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">${escapeHtml(item.title)}</p>
                    ${item.subtitle ? `<p class="mt-0.5 truncate text-xs text-gray-500">${escapeHtml(item.subtitle)}</p>` : ''}
                </div>
                <span class="shrink-0 rounded-full bg-neutral-800 px-2 py-0.5 text-[10px] uppercase tracking-wide text-gray-400">${escapeHtml(item.badge || item.type)}</span>
            `;
            results.appendChild(a);
            return a;
        });
        activeIndex = 0;
        highlightActive();
    };

    const highlightActive = () => {
        items.forEach((el, i) => {
            if (i === activeIndex) {
                el.classList.add('bg-neutral-800/60');
                el.scrollIntoView({ block: 'nearest' });
            } else {
                el.classList.remove('bg-neutral-800/60');
            }
        });
    };

    const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
    }[c]));

    const search = async (q) => {
        if (q.length < 2) {
            results.innerHTML = '';
            empty.textContent = 'Comece a digitar (mín. 2 caracteres)…';
            results.appendChild(empty);
            return;
        }
        if (abortController) abortController.abort();
        abortController = new AbortController();
        try {
            empty.textContent = 'Buscando…';
            const res = await fetch(`/blog/buscar?q=${encodeURIComponent(q)}`, { signal: abortController.signal });
            const data = await res.json();
            render(data.results || []);
        } catch (err) {
            if (err.name !== 'AbortError') {
                empty.textContent = 'Erro ao buscar.';
            }
        }
    };

    document.addEventListener('keydown', (e) => {
        const isMac = navigator.platform.toLowerCase().includes('mac');
        const cmdK = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'k';
        if (cmdK) {
            e.preventDefault();
            root.classList.contains('hidden') ? open() : close();
            return;
        }
        if (root.classList.contains('hidden')) return;
        if (e.key === 'Escape') { e.preventDefault(); close(); }
        if (e.key === 'ArrowDown') { e.preventDefault(); if (items.length) { activeIndex = (activeIndex + 1) % items.length; highlightActive(); } }
        if (e.key === 'ArrowUp') { e.preventDefault(); if (items.length) { activeIndex = (activeIndex - 1 + items.length) % items.length; highlightActive(); } }
        if (e.key === 'Enter' && activeIndex >= 0 && items[activeIndex]) {
            e.preventDefault();
            window.location.href = items[activeIndex].href;
        }
    });

    document.querySelectorAll('[data-command-palette-open]').forEach((btn) => {
        btn.addEventListener('click', open);
    });

    root.addEventListener('click', (e) => {
        if (!panel.contains(e.target)) close();
    });

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const q = input.value.trim();
        debounceTimer = setTimeout(() => search(q), 200);
    });
})();
</script>
