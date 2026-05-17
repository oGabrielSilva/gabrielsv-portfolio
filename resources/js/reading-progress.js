// Reading progress bar — vai de 0% (topo do artigo no topo da viewport)
// a 100% (fim do artigo no fim da viewport). Depois disso, fica em 100%.
const bar = document.getElementById('reading-progress');
if (bar) {
    const article = document.querySelector('article');
    if (!article) {
        bar.style.display = 'none';
    } else {
        const update = () => {
            const rect = article.getBoundingClientRect();
            const articleTop = window.scrollY + rect.top;
            const articleBottom = articleTop + article.offsetHeight;

            // Janela útil: de "topo do article tocando topo da viewport"
            // até "fim do article tocando fim da viewport"
            const start = articleTop;
            const end = articleBottom - window.innerHeight;
            const span = Math.max(1, end - start);
            const pct = ((window.scrollY - start) / span) * 100;

            bar.style.width = `${Math.min(100, Math.max(0, pct))}%`;
        };
        update();
        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update, { passive: true });
    }
}
