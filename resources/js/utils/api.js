/**
 * Thin fetch wrapper for the tool POST endpoints. Reads the CSRF token
 * from the meta tag rendered by partials/head.blade.php, so tools no
 * longer need a window.<tool>Config object.
 *
 * Throws ApiError on non-2xx responses; callers can read .status and
 * .body to render a useful message.
 */

export class ApiError extends Error {
    constructor(status, body) {
        super(`HTTP ${status}`);
        this.name = 'ApiError';
        this.status = status;
        this.body = body;
    }
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function parseResponse(res) {
    const contentType = res.headers.get('content-type') || '';
    if (contentType.includes('application/json')) {
        try {
            return await res.json();
        } catch {
            return null;
        }
    }
    return res.text();
}

export async function postJson(url, payload, { signal } = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
        signal,
    });

    const body = await parseResponse(res);
    if (!res.ok) throw new ApiError(res.status, body);
    return body;
}

export async function getJson(url, { signal } = {}) {
    const res = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        signal,
    });
    const body = await parseResponse(res);
    if (!res.ok) throw new ApiError(res.status, body);
    return body;
}
