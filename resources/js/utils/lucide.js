/**
 * Wraps the global lucide.createIcons() call so tools don't all have to
 * remember to feature-detect window.lucide. Passing a context element
 * restricts the scan to that subtree, which is faster than re-scanning
 * the whole document on every render.
 */
export function refreshIcons(ctx) {
    const lucide = window.lucide;
    if (!lucide?.createIcons) return;

    if (ctx) {
        lucide.createIcons({ nodes: [ctx] });
    } else {
        lucide.createIcons();
    }
}
