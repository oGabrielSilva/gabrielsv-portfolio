// Spotlight cursor (Lote 4.1) — atualiza vars CSS --mx/--my em elementos .spotlight
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    let frame = null;
    let lastEvent = null;

    const update = () => {
        if (!lastEvent) return;
        const els = document.querySelectorAll('.spotlight');
        els.forEach((el) => {
            const r = el.getBoundingClientRect();
            if (
                lastEvent.clientX < r.left - 200 || lastEvent.clientX > r.right + 200 ||
                lastEvent.clientY < r.top - 200 || lastEvent.clientY > r.bottom + 200
            ) return;
            el.style.setProperty('--mx', `${lastEvent.clientX - r.left}px`);
            el.style.setProperty('--my', `${lastEvent.clientY - r.top}px`);
        });
        frame = null;
    };

    document.addEventListener('mousemove', (e) => {
        lastEvent = e;
        if (!frame) frame = requestAnimationFrame(update);
    }, { passive: true });
}
