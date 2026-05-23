/**
 * Gerador de string .ics (iCalendar RFC 5545) para um único VEVENT.
 *
 * Cuida das pegadinhas que matam concorrentes:
 * - CRLF em toda linha (Outlook novo rejeita LF)
 * - Line folding em 75 octets (TextEncoder mede bytes, não chars)
 * - Escape de \\, ;, ,, \n em TEXT
 * - DTSTAMP em UTC
 * - Conversão wall-clock -> UTC respeitando DST do fuso escolhido
 *
 * Não emite VTIMEZONE: convertemos tudo pra UTC com sufixo Z. Cobre 99% dos
 * casos sem o peso do bloco de timezone (que pode ter 50+ linhas).
 */

const CRLF = '\r\n';

export function escapeText(value) {
    if (value == null) return '';
    return String(value)
        .replace(/\\/g, '\\\\')
        .replace(/;/g, '\\;')
        .replace(/,/g, '\\,')
        .replace(/\r\n|\r|\n/g, '\\n');
}

/**
 * Quebra a linha em chunks de até 75 octets, com CRLF + espaço entre eles.
 * Mede em bytes via TextEncoder porque caracteres acentuados (à, ç, é) ocupam
 * 2 bytes em UTF-8 e podem estourar o limite mesmo com poucos chars.
 */
export function foldLine(line) {
    const encoder = new TextEncoder();
    const bytes = encoder.encode(line);
    if (bytes.length <= 75) return line;

    const decoder = new TextDecoder();
    const chunks = [];
    let offset = 0;
    while (offset < bytes.length) {
        // Garante que a fatia não corta um caractere multibyte ao meio.
        let end = Math.min(offset + 75, bytes.length);
        while (end > offset && (bytes[end] & 0b11000000) === 0b10000000) {
            end -= 1;
        }
        chunks.push(decoder.decode(bytes.slice(offset, end)));
        offset = end;
    }
    return chunks.join(`${CRLF} `);
}

/**
 * Pad 2 dígitos.
 */
function pad(n) {
    return String(n).padStart(2, '0');
}

/**
 * Formata Date em UTC compacto: YYYYMMDDTHHMMSSZ.
 */
export function formatUtc(date) {
    return (
        date.getUTCFullYear().toString() +
        pad(date.getUTCMonth() + 1) +
        pad(date.getUTCDate()) +
        'T' +
        pad(date.getUTCHours()) +
        pad(date.getUTCMinutes()) +
        pad(date.getUTCSeconds()) +
        'Z'
    );
}

/**
 * Formata data (sem hora) como YYYYMMDD, usado em VALUE=DATE.
 * Aceita string "YYYY-MM-DD" do input type=date.
 */
export function formatDate(value) {
    return value.replace(/-/g, '');
}

/**
 * Calcula o offset (em minutos) de um timezone IANA numa data wall-clock.
 *
 * `wallClock` é uma string "YYYY-MM-DDTHH:MM" sem fuso (do input
 * datetime-local). Retorna quantos minutos somar à wall-clock pra obter UTC.
 *
 * Ex.: em São Paulo (UTC-3), retorna 180. Em Lisboa no verão (UTC+1), -60.
 */
export function timezoneOffsetMinutes(wallClock, timeZone) {
    if (timeZone === 'UTC') return 0;

    // Construímos uma Date "como se fosse UTC" e perguntamos ao Intl
    // que horas/data isso representaria no timezone alvo. A diferença é o offset.
    const [datePart, timePart = '00:00'] = wallClock.split('T');
    const [y, mo, d] = datePart.split('-').map(Number);
    const [h, mi] = timePart.split(':').map(Number);

    const asUtc = Date.UTC(y, mo - 1, d, h, mi, 0);

    const dtf = new Intl.DateTimeFormat('en-US', {
        timeZone,
        hour12: false,
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
    const parts = Object.fromEntries(
        dtf.formatToParts(new Date(asUtc)).map(p => [p.type, p.value]),
    );
    const tzAsUtc = Date.UTC(
        Number(parts.year),
        Number(parts.month) - 1,
        Number(parts.day),
        Number(parts.hour === '24' ? 0 : parts.hour),
        Number(parts.minute),
        Number(parts.second),
    );

    // Diferença em minutos. Se positivo, o fuso está atrás do UTC.
    return Math.round((asUtc - tzAsUtc) / 60000);
}

/**
 * Converte "YYYY-MM-DDTHH:MM" (wall-clock no timezone informado) em Date UTC.
 */
export function wallClockToUtc(wallClock, timeZone) {
    const offsetMin = timezoneOffsetMinutes(wallClock, timeZone);
    const [datePart, timePart = '00:00'] = wallClock.split('T');
    const [y, mo, d] = datePart.split('-').map(Number);
    const [h, mi] = timePart.split(':').map(Number);
    return new Date(Date.UTC(y, mo - 1, d, h, mi, 0) + offsetMin * 60000);
}

/**
 * Gera UID estável: timestamp + hex aleatório + domínio.
 */
export function generateUid(domain = 'eu.gabrielsv.com') {
    const rand = Array.from({ length: 8 }, () =>
        Math.floor(Math.random() * 16).toString(16),
    ).join('');
    return `${Date.now()}-${rand}@${domain}`;
}

const REMINDER_TO_TRIGGER = {
    '5': '-PT5M',
    '10': '-PT10M',
    '15': '-PT15M',
    '30': '-PT30M',
    '60': '-PT1H',
    '1440': '-P1D',
};

const RECUR_TO_FREQ = {
    daily: 'DAILY',
    weekly: 'WEEKLY',
    monthly: 'MONTHLY',
    yearly: 'YEARLY',
};

/**
 * Monta a string .ics completa para um VEVENT.
 *
 * event = {
 *   uid: string (opcional, gerado se ausente)
 *   title: string (obrigatório)
 *   description?: string
 *   location?: string
 *   url?: string
 *   start: string ("YYYY-MM-DDTHH:MM" ou "YYYY-MM-DD" se allDay)
 *   end: string (mesmo formato de start)
 *   allDay?: boolean
 *   timezone?: string (default America/Sao_Paulo)
 *   reminderMinutes?: string ("5"|"10"|"15"|"30"|"60"|"1440") ou vazio
 *   recurrence?: "daily"|"weekly"|"monthly"|"yearly" ou vazio
 *   recurUntil?: string ("YYYY-MM-DD") opcional, fim da recorrência
 * }
 */
export function buildIcs(event) {
    const {
        uid = generateUid(),
        title,
        description,
        location,
        url,
        start,
        end,
        allDay = false,
        timezone = 'America/Sao_Paulo',
        reminderMinutes,
        recurrence,
        recurUntil,
    } = event;

    if (!title) throw new Error('title é obrigatório');
    if (!start) throw new Error('start é obrigatório');
    if (!end) throw new Error('end é obrigatório');

    const lines = [
        'BEGIN:VCALENDAR',
        'VERSION:2.0',
        'PRODID:-//eu.gabrielsv.com//ICS Generator//PT-BR',
        'CALSCALE:GREGORIAN',
        'METHOD:PUBLISH',
        'BEGIN:VEVENT',
        `UID:${uid}`,
        `DTSTAMP:${formatUtc(new Date())}`,
    ];

    if (allDay) {
        lines.push(`DTSTART;VALUE=DATE:${formatDate(start)}`);
        lines.push(`DTEND;VALUE=DATE:${formatDate(end)}`);
    } else {
        lines.push(`DTSTART:${formatUtc(wallClockToUtc(start, timezone))}`);
        lines.push(`DTEND:${formatUtc(wallClockToUtc(end, timezone))}`);
    }

    lines.push(`SUMMARY:${escapeText(title)}`);
    if (description) lines.push(`DESCRIPTION:${escapeText(description)}`);
    if (location) lines.push(`LOCATION:${escapeText(location)}`);
    if (url) lines.push(`URL:${url}`);

    if (recurrence && RECUR_TO_FREQ[recurrence]) {
        let rrule = `RRULE:FREQ=${RECUR_TO_FREQ[recurrence]}`;
        if (recurUntil) {
            // UNTIL precisa ser UTC. Usamos 23:59:59 do dia escolhido.
            const untilDate = wallClockToUtc(`${recurUntil}T23:59`, timezone);
            rrule += `;UNTIL=${formatUtc(untilDate)}`;
        }
        lines.push(rrule);
    }

    if (reminderMinutes && REMINDER_TO_TRIGGER[reminderMinutes]) {
        lines.push('BEGIN:VALARM');
        lines.push('ACTION:DISPLAY');
        lines.push(`TRIGGER:${REMINDER_TO_TRIGGER[reminderMinutes]}`);
        lines.push('DESCRIPTION:Lembrete');
        lines.push('END:VALARM');
    }

    lines.push('END:VEVENT');
    lines.push('END:VCALENDAR');

    return lines.map(foldLine).join(CRLF) + CRLF;
}
