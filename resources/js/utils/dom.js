/**
 * DOM helpers shared by every tool.
 *
 * escapeHtml — safe to inject as HTML text content
 * escapeAttr — safe to interpolate inside an HTML attribute value
 * $, $$     — short query helpers, optionally scoped
 */

export function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text == null ? '' : String(text);
    return div.innerHTML;
}

export function escapeAttr(text) {
    if (text == null) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

export function $(selector, ctx = document) {
    return ctx.querySelector(selector);
}

export function $$(selector, ctx = document) {
    return Array.from(ctx.querySelectorAll(selector));
}
