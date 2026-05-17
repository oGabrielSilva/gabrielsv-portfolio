// Reading progress bar — atualiza largura conforme scroll
const bar = document.getElementById('reading-progress');
if (bar) {
    const update = () => {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const pct = max > 0 ? Math.min(100, (window.scrollY / max) * 100) : 0;
        bar.style.width = `${pct}%`;
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
}
