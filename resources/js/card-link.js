/**
 * Card-link: torna qualquer elemento com [data-card-link="/url"] um hit area clicável,
 * preservando o link real (no título) para acessibilidade, abrir em nova aba (Cmd/Ctrl+clique,
 * clique do meio) e seleção de texto.
 *
 * Não dispara em clique dentro de <a>, <button>, <input>, [role="button"] internos —
 * eles continuam com comportamento próprio (ex: chip de categoria).
 */
document.addEventListener("click", (e) => {
    const card = e.target.closest("[data-card-link]");
    if (!card) return;

    // Se o clique foi num link/botão/input real, deixa o navegador cuidar
    if (e.target.closest("a, button, input, label, [role='button']")) return;

    // Ignora se o usuário está selecionando texto
    const selection = window.getSelection();
    if (selection && selection.toString().length > 0) return;

    const href = card.dataset.cardLink;
    if (!href) return;

    // Cmd/Ctrl/Shift+clique ou clique do meio → nova aba
    if (e.metaKey || e.ctrlKey || e.shiftKey) {
        window.open(href, "_blank", "noopener");
        return;
    }

    window.location.href = href;
});

// Clique do meio (auxclick botão 1) → nova aba
document.addEventListener("auxclick", (e) => {
    if (e.button !== 1) return;
    const card = e.target.closest("[data-card-link]");
    if (!card) return;
    if (e.target.closest("a, button, input, label, [role='button']")) return;

    const href = card.dataset.cardLink;
    if (!href) return;

    e.preventDefault();
    window.open(href, "_blank", "noopener");
});
