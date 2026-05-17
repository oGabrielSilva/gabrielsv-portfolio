// Copy link no share-buttons (Lote 1.4)
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-copy-url]');
    if (!btn) return;
    e.preventDefault();
    const url = btn.dataset.copyUrl;
    const label = btn.querySelector('[data-copy-label]');
    const original = label?.textContent;

    try {
        await navigator.clipboard.writeText(url);
        if (label) label.textContent = 'Link copiado!';
        setTimeout(() => { if (label && original) label.textContent = original; }, 1800);
    } catch (_) {
        if (label) label.textContent = 'Falhou';
        setTimeout(() => { if (label && original) label.textContent = original; }, 1800);
    }
});
