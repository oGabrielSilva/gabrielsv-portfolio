// Copy button em code blocks (Lote 1.7)
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-copy]');
    if (!btn) return;
    const block = btn.closest('.code-block');
    const code = block?.querySelector('code');
    if (!code) return;

    try {
        await navigator.clipboard.writeText(code.textContent.trim());
        const original = btn.textContent;
        btn.textContent = 'Copiado!';
        btn.style.background = 'var(--color-bulma-primary)';
        btn.style.color = '#0a0a0a';
        setTimeout(() => {
            btn.textContent = original;
            btn.style.background = '';
            btn.style.color = '';
        }, 1500);
    } catch (_) {
        btn.textContent = 'Falhou';
        setTimeout(() => (btn.textContent = 'Copiar'), 1500);
    }
});
