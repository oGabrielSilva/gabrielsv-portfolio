/**
 * Cross-context clipboard copy. navigator.clipboard isn't available on
 * insecure origins (e.g. http://192.168.x.x dev URLs) so we fall back to
 * a hidden textarea + execCommand for those cases.
 *
 * Throws if both paths fail — callers should catch and surface an error.
 */
export async function copyText(text) {
    const value = text == null ? '' : String(text);

    if (navigator.clipboard?.writeText) {
        try {
            await navigator.clipboard.writeText(value);
            return;
        } catch {
            // fall through to legacy path
        }
    }

    const ta = document.createElement('textarea');
    ta.value = value;
    ta.setAttribute('readonly', '');
    ta.style.position = 'fixed';
    ta.style.top = '-1000px';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    try {
        const ok = document.execCommand('copy');
        if (!ok) throw new Error('execCommand("copy") returned false');
    } finally {
        ta.remove();
    }
}
