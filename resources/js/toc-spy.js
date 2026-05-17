// TOC scroll spy — destaca o link da seção visível
const tocLinks = document.querySelectorAll('[data-toc-link]');
if (tocLinks.length > 0 && 'IntersectionObserver' in window) {
    const linkById = new Map();
    tocLinks.forEach((link) => linkById.set(link.dataset.tocLink, link));

    const headings = Array.from(linkById.keys())
        .map((id) => document.getElementById(id))
        .filter(Boolean);

    if (headings.length > 0) {
        let activeId = null;

        const setActive = (id) => {
            if (activeId === id) return;
            tocLinks.forEach((link) => {
                const isActive = link.dataset.tocLink === id;
                link.classList.toggle('is-active', isActive);
                link.closest('.toc__item')?.classList.toggle('is-active', isActive);
            });
            activeId = id;
        };

        const observer = new IntersectionObserver((entries) => {
            const visible = entries
                .filter((e) => e.isIntersecting)
                .sort((a, b) => a.target.offsetTop - b.target.offsetTop);
            if (visible.length > 0) {
                setActive(visible[0].target.id);
            }
        }, {
            rootMargin: '-80px 0px -60% 0px',
            threshold: 0,
        });

        headings.forEach((h) => observer.observe(h));
    }
}
