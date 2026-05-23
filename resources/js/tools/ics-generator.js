import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';
import { buildIcs, generateUid } from '../utils/ics-builder.js';
import {
    buildGoogleLink,
    buildOutlookLink,
    buildOffice365Link,
    buildYahooLink,
} from '../utils/calendar-links.js';

const PROVIDER_BUILDERS = {
    google: buildGoogleLink,
    outlook: buildOutlookLink,
    office365: buildOffice365Link,
    yahoo: buildYahooLink,
};

const FIELDS = [
    'title', 'description', 'location', 'url',
    'start', 'end', 'timezone', 'reminder', 'recurrence', 'recurUntil',
];

function safeDecodeHash(hash) {
    if (!hash || !hash.startsWith('#data=')) return null;
    try {
        const raw = decodeURIComponent(atob(hash.slice(6)));
        const parsed = JSON.parse(raw);
        return typeof parsed === 'object' && parsed !== null ? parsed : null;
    } catch {
        return null;
    }
}

function encodeHash(state) {
    try {
        return '#data=' + btoa(encodeURIComponent(JSON.stringify(state)));
    } catch {
        return '';
    }
}

function debounce(fn, ms) {
    let timer = null;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), ms);
    };
}

class IcsGenerator {
    constructor(root) {
        this.root = root;
        this.uid = generateUid();

        // Form fields
        this.fields = {
            title: document.getElementById('ics-title'),
            description: document.getElementById('ics-description'),
            location: document.getElementById('ics-location'),
            url: document.getElementById('ics-url'),
            allDay: document.getElementById('ics-all-day'),
            start: document.getElementById('ics-start'),
            end: document.getElementById('ics-end'),
            timezone: document.getElementById('ics-timezone'),
            reminder: document.getElementById('ics-reminder'),
            recurrence: document.getElementById('ics-recurrence'),
            recurUntilWrap: document.getElementById('ics-recur-until-wrap'),
            recurUntil: document.getElementById('ics-recur-until'),
        };

        // Outputs
        this.output = document.getElementById('ics-output');
        this.copyBtn = document.getElementById('ics-copy');
        this.downloadBtn = document.getElementById('ics-download');
        this.permalink = document.getElementById('ics-permalink');
        this.permalinkCopy = document.getElementById('ics-permalink-copy');
        this.permalinkOpen = document.getElementById('ics-permalink-open');
        this.appleDownload = document.getElementById('ics-apple-download');
        this.status = document.getElementById('ics-status');

        this.providers = Array.from(root.querySelectorAll('[data-provider]'));

        this.bindEvents();
        this.restoreFromHashOrDefaults();
        this.updateHash = debounce(() => this.persistToHash(), 300);
        this.render();
    }

    bindEvents() {
        const inputs = [
            'title', 'description', 'location', 'url',
            'start', 'end', 'timezone', 'reminder', 'recurUntil',
        ];
        inputs.forEach(key => {
            this.fields[key]?.addEventListener('input', () => this.render());
        });

        this.fields.allDay.addEventListener('change', () => {
            this.toggleAllDay();
            this.render();
        });

        this.fields.recurrence.addEventListener('change', () => {
            const hasRecurrence = !!this.fields.recurrence.value;
            this.fields.recurUntilWrap.classList.toggle('hidden', !hasRecurrence);
            this.render();
        });

        this.copyBtn.addEventListener('click', () => this.copyIcs());
        this.downloadBtn.addEventListener('click', () => this.downloadIcs());
        this.appleDownload.addEventListener('click', () => this.downloadIcs());
        this.permalinkCopy.addEventListener('click', () => this.copyPermalink());

        this.providers.forEach(li => {
            const key = li.dataset.provider;
            li.querySelector('[data-action="copy"]').addEventListener('click', () => this.copyProvider(key));
        });

        window.addEventListener('hashchange', () => {
            if (this.suppressNextHashChange) {
                this.suppressNextHashChange = false;
                return;
            }
            this.restoreFromHashOrDefaults();
            this.render();
        });
    }

    toggleAllDay() {
        const allDay = this.fields.allDay.checked;
        const startVal = this.fields.start.value;
        const endVal = this.fields.end.value;

        if (allDay) {
            this.fields.start.type = 'date';
            this.fields.end.type = 'date';
            if (startVal && startVal.includes('T')) this.fields.start.value = startVal.split('T')[0];
            if (endVal && endVal.includes('T')) this.fields.end.value = endVal.split('T')[0];
        } else {
            this.fields.start.type = 'datetime-local';
            this.fields.end.type = 'datetime-local';
            if (startVal && !startVal.includes('T')) this.fields.start.value = startVal + 'T09:00';
            if (endVal && !endVal.includes('T')) this.fields.end.value = endVal + 'T10:00';
        }
    }

    restoreFromHashOrDefaults() {
        const fromHash = safeDecodeHash(window.location.hash);
        if (fromHash) {
            this.applyState(fromHash);
        } else {
            this.applyDefaults();
        }
    }

    applyDefaults() {
        // Default: amanhã, 9h-10h, 15min lembrete.
        const now = new Date();
        const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
        const y = tomorrow.getFullYear();
        const m = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const d = String(tomorrow.getDate()).padStart(2, '0');
        this.fields.start.value = `${y}-${m}-${d}T09:00`;
        this.fields.end.value = `${y}-${m}-${d}T10:00`;
    }

    applyState(state) {
        const set = (el, val) => { if (el && val != null) el.value = val; };
        set(this.fields.title, state.title);
        set(this.fields.description, state.description);
        set(this.fields.location, state.location);
        set(this.fields.url, state.url);
        set(this.fields.timezone, state.timezone || 'America/Sao_Paulo');
        set(this.fields.reminder, state.reminder ?? '15');
        set(this.fields.recurrence, state.recurrence);
        set(this.fields.recurUntil, state.recurUntil);

        if (state.allDay) {
            this.fields.allDay.checked = true;
            this.fields.start.type = 'date';
            this.fields.end.type = 'date';
        } else {
            this.fields.allDay.checked = false;
            this.fields.start.type = 'datetime-local';
            this.fields.end.type = 'datetime-local';
        }
        set(this.fields.start, state.start);
        set(this.fields.end, state.end);

        if (state.recurrence) {
            this.fields.recurUntilWrap.classList.remove('hidden');
        }

        if (state.uid) this.uid = state.uid;
    }

    readState() {
        return {
            title: this.fields.title.value.trim(),
            description: this.fields.description.value,
            location: this.fields.location.value.trim(),
            url: this.fields.url.value.trim(),
            allDay: this.fields.allDay.checked,
            start: this.fields.start.value,
            end: this.fields.end.value,
            timezone: this.fields.timezone.value,
            reminder: this.fields.reminder.value,
            recurrence: this.fields.recurrence.value,
            recurUntil: this.fields.recurUntil.value,
            uid: this.uid,
        };
    }

    validate(state) {
        if (!state.title) return 'Informe o título do evento.';
        if (!state.start) return 'Informe a data e hora de início.';
        if (!state.end) return 'Informe a data e hora de fim.';

        // Comparação string funciona para "YYYY-MM-DDTHH:MM" e "YYYY-MM-DD".
        if (state.end < state.start) return 'O fim deve ser igual ou depois do início.';
        return null;
    }

    render() {
        const state = this.readState();
        const error = this.validate(state);

        if (error) {
            this.showStatus('error', error);
            this.clearOutputs();
            this.updateHash?.(); // ainda atualiza hash com state parcial
            return;
        }
        this.showStatus(null);

        const eventForBuilder = {
            uid: state.uid,
            title: state.title,
            description: state.description || undefined,
            location: state.location || undefined,
            url: state.url || undefined,
            start: state.start,
            end: state.end,
            allDay: state.allDay,
            timezone: state.timezone,
            reminderMinutes: state.reminder || undefined,
            recurrence: state.recurrence || undefined,
            recurUntil: state.recurUntil || undefined,
        };

        try {
            this.icsText = buildIcs(eventForBuilder);
            this.output.textContent = this.icsText;
        } catch (err) {
            this.showStatus('error', err.message || 'Erro ao montar o .ics');
            this.clearOutputs();
            return;
        }

        // Permalink
        const hash = encodeHash(state);
        const permalinkUrl = window.location.origin + window.location.pathname + hash;
        this.permalink.textContent = permalinkUrl;
        this.permalinkOpen.href = permalinkUrl;
        this.permalinkOpen.classList.remove('opacity-50', 'pointer-events-none');

        // Provider links
        const providerEvent = {
            title: state.title,
            description: state.description,
            location: state.location,
            start: state.start,
            end: state.end,
            allDay: state.allDay,
            timezone: state.timezone,
            recurrence: state.recurrence,
            recurUntil: state.recurUntil,
        };
        this.providers.forEach(li => {
            const key = li.dataset.provider;
            const url = PROVIDER_BUILDERS[key](providerEvent);
            const openLink = li.querySelector('[data-action="open"]');
            openLink.href = url;
            openLink.dataset.url = url;
            openLink.classList.remove('opacity-50', 'pointer-events-none');
        });

        this.updateHash?.();
    }

    persistToHash() {
        const state = this.readState();
        const hash = encodeHash(state);
        if (window.location.hash === hash) return;
        this.suppressNextHashChange = true;
        history.replaceState(null, '', window.location.pathname + hash);
    }

    clearOutputs() {
        this.output.textContent = '—';
        this.permalink.textContent = '—';
        this.permalinkOpen.removeAttribute('href');
        this.permalinkOpen.classList.add('opacity-50', 'pointer-events-none');
        this.providers.forEach(li => {
            const openLink = li.querySelector('[data-action="open"]');
            openLink.removeAttribute('href');
            openLink.classList.add('opacity-50', 'pointer-events-none');
        });
        this.icsText = '';
    }

    showStatus(kind, message = '') {
        if (!kind) {
            this.status.classList.add('hidden');
            return;
        }
        this.status.classList.remove(
            'hidden',
            'bg-red-500/10', 'text-red-400',
            'bg-amber-500/10', 'text-amber-400',
        );
        if (kind === 'error') {
            this.status.classList.add('bg-red-500/10', 'text-red-400');
        } else {
            this.status.classList.add('bg-amber-500/10', 'text-amber-400');
        }
        this.status.textContent = message;
    }

    async copyIcs() {
        if (!this.icsText) {
            showToast('Preencha os campos primeiro', { variant: 'error' });
            return;
        }
        try {
            await copyText(this.icsText);
            showToast('Arquivo .ics copiado!');
        } catch {
            showToast('Não foi possível copiar', { variant: 'error' });
        }
    }

    downloadIcs() {
        if (!this.icsText) {
            showToast('Preencha os campos primeiro', { variant: 'error' });
            return;
        }
        const state = this.readState();
        const safeName = (state.title || 'evento')
            .toLowerCase()
            .replace(/[^a-z0-9-_]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .slice(0, 60) || 'evento';

        const blob = new Blob([this.icsText], { type: 'text/calendar;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${safeName}.ics`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    }

    async copyPermalink() {
        const url = this.permalink.textContent.trim();
        if (!url || url === '—') {
            showToast('Preencha os campos primeiro', { variant: 'error' });
            return;
        }
        try {
            await copyText(url);
            showToast('Link copiado!');
        } catch {
            showToast('Não foi possível copiar', { variant: 'error' });
        }
    }

    async copyProvider(key) {
        const li = this.providers.find(el => el.dataset.provider === key);
        const url = li?.querySelector('[data-action="open"]')?.dataset.url;
        if (!url) {
            showToast('Preencha os campos primeiro', { variant: 'error' });
            return;
        }
        try {
            await copyText(url);
            showToast('URL copiada!');
        } catch {
            showToast('Não foi possível copiar', { variant: 'error' });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="ics-generator"]');
    if (root) new IcsGenerator(root);
});
