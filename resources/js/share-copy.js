// Web Share API + copy link no share-buttons
const shareRoots = document.querySelectorAll('[data-share]');
shareRoots.forEach((root) => {
    if (!('share' in navigator)) return;
    const nativeBtn = root.querySelector('[data-share-native]');
    const fallback = root.querySelector('[data-share-fallback]');
    if (!nativeBtn) return;

    nativeBtn.hidden = false;
    if (fallback) fallback.hidden = true;

    nativeBtn.addEventListener('click', async () => {
        try {
            await navigator.share({
                title: root.dataset.shareTitle || document.title,
                text: root.dataset.shareText || '',
                url: root.dataset.shareUrl || window.location.href,
            });
        } catch (_) {
            // user cancel ou erro — sem ação
        }
    });
});

// Copy link (sempre disponível)
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
