/**
 * Montagem de URLs "Adicionar ao calendário" por provedor.
 *
 * Cada provedor tem um formato próprio. Apple Calendar não tem URL scheme
 * para web (só nativo iOS/macOS), então não está aqui — quem usa Apple
 * baixa o .ics diretamente.
 *
 * Formatos baseados no que está implementado pelos providers em maio/2026.
 */

import {
    formatUtc,
    formatDate,
    wallClockToUtc,
} from './ics-builder.js';

/**
 * Normaliza datas conforme tipo de evento (allDay vs com hora).
 * Retorna { startUtc: Date, endUtc: Date } ou, no caso allDay,
 * { startDate, endDate } como strings YYYYMMDD.
 */
function normalizeDates({ start, end, allDay, timezone }) {
    if (allDay) {
        return {
            allDay: true,
            startDate: formatDate(start),
            endDate: formatDate(end),
        };
    }
    return {
        allDay: false,
        startUtc: wallClockToUtc(start, timezone),
        endUtc: wallClockToUtc(end, timezone),
    };
}

/**
 * ISO sem ms, sem timezone (Outlook quer "YYYY-MM-DDTHH:MM:SS" em UTC).
 */
function utcIsoCompact(date) {
    const pad = n => String(n).padStart(2, '0');
    return (
        date.getUTCFullYear() +
        '-' +
        pad(date.getUTCMonth() + 1) +
        '-' +
        pad(date.getUTCDate()) +
        'T' +
        pad(date.getUTCHours()) +
        ':' +
        pad(date.getUTCMinutes()) +
        ':' +
        pad(date.getUTCSeconds())
    );
}

const GOOGLE_RECUR = {
    daily: 'RRULE:FREQ=DAILY',
    weekly: 'RRULE:FREQ=WEEKLY',
    monthly: 'RRULE:FREQ=MONTHLY',
    yearly: 'RRULE:FREQ=YEARLY',
};

/**
 * Google Calendar
 * Recorrência: aceita via param `recur`.
 * https://calendar.google.com/calendar/render?action=TEMPLATE
 *   &text=...&dates=START/END&details=...&location=...&recur=RRULE:FREQ=DAILY
 */
export function buildGoogleLink(event) {
    const dates = normalizeDates(event);
    const params = new URLSearchParams();
    params.set('action', 'TEMPLATE');
    params.set('text', event.title || '');

    if (dates.allDay) {
        params.set('dates', `${dates.startDate}/${dates.endDate}`);
    } else {
        params.set('dates', `${formatUtc(dates.startUtc)}/${formatUtc(dates.endUtc)}`);
    }

    if (event.description) params.set('details', event.description);
    if (event.location) params.set('location', event.location);
    if (event.recurrence && GOOGLE_RECUR[event.recurrence]) {
        let recur = GOOGLE_RECUR[event.recurrence];
        if (event.recurUntil) {
            const until = wallClockToUtc(
                `${event.recurUntil}T23:59`,
                event.timezone || 'America/Sao_Paulo',
            );
            recur += `;UNTIL=${formatUtc(until)}`;
        }
        params.set('recur', recur);
    }

    return `https://calendar.google.com/calendar/render?${params.toString()}`;
}

/**
 * Outlook.com (conta pessoal Microsoft).
 * Base oficial: https://outlook.live.com/calendar/deeplink/compose
 * (Não usar /calendar/0/deeplink — a versão com /0/ joga o request num
 *  fluxo do Azure AD que estoura 2048 chars no querystring -> AADSTS90015.)
 */
export function buildOutlookLink(event) {
    return buildOutlookBase('https://outlook.live.com/calendar/deeplink/compose', event);
}

/**
 * Office 365 (conta corporativa).
 * Mesma URL base, host diferente.
 */
export function buildOffice365Link(event) {
    return buildOutlookBase('https://outlook.office.com/calendar/deeplink/compose', event);
}

function buildOutlookBase(base, event) {
    const dates = normalizeDates(event);
    const params = new URLSearchParams();
    params.set('path', '/calendar/action/compose');
    params.set('rru', 'addevent');
    params.set('subject', event.title || '');

    if (dates.allDay) {
        // Outlook all-day usa só a data (sem hora) e marca allday=true.
        params.set('startdt', event.start);
        params.set('enddt', event.end);
        params.set('allday', 'true');
    } else {
        params.set('startdt', utcIsoCompact(dates.startUtc) + 'Z');
        params.set('enddt', utcIsoCompact(dates.endUtc) + 'Z');
    }

    if (event.description) params.set('body', event.description);
    if (event.location) params.set('location', event.location);

    return `${base}?${params.toString()}`;
}

/**
 * Yahoo Calendar
 * https://calendar.yahoo.com/?v=60&title=...&st=YYYYMMDDTHHMMSSZ&et=YYYYMMDDTHHMMSSZ
 *   &desc=...&in_loc=...
 *
 * Yahoo all-day usa `dur=allday` em vez de et.
 */
export function buildYahooLink(event) {
    const dates = normalizeDates(event);
    const params = new URLSearchParams();
    params.set('v', '60');
    params.set('title', event.title || '');

    if (dates.allDay) {
        params.set('st', dates.startDate);
        params.set('dur', 'allday');
    } else {
        params.set('st', formatUtc(dates.startUtc));
        params.set('et', formatUtc(dates.endUtc));
    }

    if (event.description) params.set('desc', event.description);
    if (event.location) params.set('in_loc', event.location);

    return `https://calendar.yahoo.com/?${params.toString()}`;
}
