// Smooth scroll para back-to-top do footer
document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href="#top"]');
    if (!link) return;
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
