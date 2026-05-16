/**
 * Singleton toast. Injects its own element on first use, so views no
 * longer need to ship duplicated <div id="toast"> markup.
 *
 * Variants are colour-coded via data-variant; CSS lives in app.css.
 *
 *   showToast('Copiado!');
 *   showToast('Falha ao copiar', { variant: 'error', duration: 3000 });
 */

const VARIANTS = ['success', 'error', 'info'];

let toastEl = null;
let textEl = null;
let iconEl = null;
let hideTimer = null;

function ensureElement() {
    if (toastEl) return;

    toastEl = document.createElement('div');
    toastEl.id = 'app-toast';
    toastEl.setAttribute('role', 'status');
    toastEl.setAttribute('aria-live', 'polite');
    toastEl.dataset.variant = 'success';
    toastEl.className = [
        'fixed bottom-4 right-4 z-[60]',
        'py-3 px-4 rounded-lg shadow-lg font-medium',
        'inline-flex items-center gap-2',
        'transform translate-y-2 opacity-0 transition-all duration-300',
        'pointer-events-none',
        'bg-bulma-primary text-neutral-900',
    ].join(' ');

    iconEl = document.createElement('span');
    iconEl.className = 'inline-flex items-center';
    iconEl.textContent = '✓';
    iconEl.setAttribute('aria-hidden', 'true');

    textEl = document.createElement('span');
    textEl.textContent = '';

    toastEl.append(iconEl, textEl);
    document.body.append(toastEl);
}

function applyVariant(variant) {
    toastEl.classList.remove(
        'bg-bulma-primary', 'text-neutral-900',
        'bg-red-500', 'text-white',
        'bg-neutral-700', 'text-gray-100',
    );

    switch (variant) {
        case 'error':
            toastEl.classList.add('bg-red-500', 'text-white');
            iconEl.textContent = '!';
            break;
        case 'info':
            toastEl.classList.add('bg-neutral-700', 'text-gray-100');
            iconEl.textContent = 'i';
            break;
        case 'success':
        default:
            toastEl.classList.add('bg-bulma-primary', 'text-neutral-900');
            iconEl.textContent = '✓';
            break;
    }
    toastEl.dataset.variant = variant;
}

export function showToast(message, { duration = 2000, variant = 'success' } = {}) {
    ensureElement();
    const safeVariant = VARIANTS.includes(variant) ? variant : 'success';
    applyVariant(safeVariant);
    textEl.textContent = message == null ? '' : String(message);

    toastEl.classList.remove('translate-y-2', 'opacity-0');
    toastEl.classList.add('translate-y-0', 'opacity-100');

    clearTimeout(hideTimer);
    hideTimer = setTimeout(hideToast, duration);
}

export function hideToast() {
    if (!toastEl) return;
    toastEl.classList.remove('translate-y-0', 'opacity-100');
    toastEl.classList.add('translate-y-2', 'opacity-0');
}
