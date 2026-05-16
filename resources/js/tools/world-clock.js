import { refreshIcons } from '../utils/lucide.js';
import { escapeHtml, escapeAttr } from '../utils/dom.js';
import { getJson, ApiError } from '../utils/api.js';
import { showToast } from '../utils/toast.js';

const SEARCH_DEBOUNCE_MS = 350;

// Popular cities exposed as zero-network defaults; users can also add any
// city in the world via the Nominatim-backed search.
const POPULAR_CITIES = [
    { name: 'São Paulo', country: 'Brasil', tz: 'America/Sao_Paulo', flag: '🇧🇷' },
    { name: 'Nova York', country: 'EUA', tz: 'America/New_York', flag: '🇺🇸' },
    { name: 'Londres', country: 'Reino Unido', tz: 'Europe/London', flag: '🇬🇧' },
    { name: 'Tóquio', country: 'Japão', tz: 'Asia/Tokyo', flag: '🇯🇵' },
    { name: 'Sydney', country: 'Austrália', tz: 'Australia/Sydney', flag: '🇦🇺' },
    { name: 'Dubai', country: 'Emirados Árabes', tz: 'Asia/Dubai', flag: '🇦🇪' },
    { name: 'Los Angeles', country: 'EUA', tz: 'America/Los_Angeles', flag: '🇺🇸' },
    { name: 'Paris', country: 'França', tz: 'Europe/Paris', flag: '🇫🇷' },
    { name: 'Berlim', country: 'Alemanha', tz: 'Europe/Berlin', flag: '🇩🇪' },
    { name: 'Moscou', country: 'Rússia', tz: 'Europe/Moscow', flag: '🇷🇺' },
    { name: 'Pequim', country: 'China', tz: 'Asia/Shanghai', flag: '🇨🇳' },
    { name: 'Mumbai', country: 'Índia', tz: 'Asia/Kolkata', flag: '🇮🇳' },
    { name: 'Seul', country: 'Coreia do Sul', tz: 'Asia/Seoul', flag: '🇰🇷' },
    { name: 'Singapura', country: 'Singapura', tz: 'Asia/Singapore', flag: '🇸🇬' },
    { name: 'Hong Kong', country: 'China', tz: 'Asia/Hong_Kong', flag: '🇭🇰' },
    { name: 'Bangkok', country: 'Tailândia', tz: 'Asia/Bangkok', flag: '🇹🇭' },
    { name: 'Cairo', country: 'Egito', tz: 'Africa/Cairo', flag: '🇪🇬' },
    { name: 'Istambul', country: 'Turquia', tz: 'Europe/Istanbul', flag: '🇹🇷' },
    { name: 'Toronto', country: 'Canadá', tz: 'America/Toronto', flag: '🇨🇦' },
    { name: 'Cidade do México', country: 'México', tz: 'America/Mexico_City', flag: '🇲🇽' },
    { name: 'Buenos Aires', country: 'Argentina', tz: 'America/Argentina/Buenos_Aires', flag: '🇦🇷' },
    { name: 'Lisboa', country: 'Portugal', tz: 'Europe/Lisbon', flag: '🇵🇹' },
    { name: 'Madri', country: 'Espanha', tz: 'Europe/Madrid', flag: '🇪🇸' },
    { name: 'Roma', country: 'Itália', tz: 'Europe/Rome', flag: '🇮🇹' },
    { name: 'Jacarta', country: 'Indonésia', tz: 'Asia/Jakarta', flag: '🇮🇩' },
    { name: 'Santiago', country: 'Chile', tz: 'America/Santiago', flag: '🇨🇱' },
    { name: 'Lima', country: 'Peru', tz: 'America/Lima', flag: '🇵🇪' },
    { name: 'Bogotá', country: 'Colômbia', tz: 'America/Bogota', flag: '🇨🇴' },
    { name: 'Anchorage', country: 'EUA', tz: 'America/Anchorage', flag: '🇺🇸' },
    { name: 'Honolulu', country: 'EUA', tz: 'Pacific/Honolulu', flag: '🇺🇸' },
];

// Each active clock is keyed by its IANA tz and stores display metadata
// so we don't have to look it up on every tick. activeCities is the
// ordered list of keys.
function countryCodeToFlag(cc) {
    if (!cc || cc.length !== 2) return '🌍';
    const base = 0x1F1E6 - 'A'.charCodeAt(0);
    return String.fromCodePoint(base + cc.charCodeAt(0)) + String.fromCodePoint(base + cc.charCodeAt(1));
}

class WorldClock {
    constructor(root) {
        this.root = root;
        this.searchUrl = root.dataset.searchUrl;

        this.localTime = document.getElementById('local-time');
        this.localDate = document.getElementById('local-date');
        this.localTimezone = document.getElementById('local-timezone');
        this.clocksGrid = document.getElementById('clocks-grid');
        this.citySearch = document.getElementById('city-search');
        this.searchResults = document.getElementById('search-results');

        this.searchTimer = null;
        this.searchAbort = null;

        const saved = this.loadSaved();
        this.activeCities = saved.cities;
        this.cityMeta = saved.meta;

        // Seed cityMeta with popular city info for any TZs that came from
        // the legacy default set.
        for (const tz of this.activeCities) {
            if (!this.cityMeta[tz]) {
                const pop = POPULAR_CITIES.find(c => c.tz === tz);
                if (pop) this.cityMeta[tz] = { name: pop.name, country: pop.country, flag: pop.flag };
                else this.cityMeta[tz] = { name: tz, country: '', flag: '🌍' };
            }
        }

        this.init();
    }

    loadSaved() {
        const defaults = {
            cities: [
                'America/Sao_Paulo', 'America/New_York', 'Europe/London',
                'Asia/Tokyo', 'Australia/Sydney', 'Asia/Dubai',
            ],
            meta: {},
        };
        try {
            const raw = localStorage.getItem('world-clock-state-v2');
            if (raw) {
                const parsed = JSON.parse(raw);
                return {
                    cities: Array.isArray(parsed.cities) ? parsed.cities : defaults.cities,
                    meta: parsed.meta && typeof parsed.meta === 'object' ? parsed.meta : {},
                };
            }
            // Migrate v1 (just an array of tz strings).
            const legacy = localStorage.getItem('world-clock-cities');
            if (legacy) {
                const cities = JSON.parse(legacy);
                if (Array.isArray(cities)) return { cities, meta: {} };
            }
        } catch {
            // fall through
        }
        return defaults;
    }

    saveState() {
        localStorage.setItem('world-clock-state-v2', JSON.stringify({
            cities: this.activeCities,
            meta: this.cityMeta,
        }));
    }

    init() {
        this.renderClocks();
        this.updateAll();
        this.startTicking();

        this.handleVisibility = () => {
            if (document.visibilityState === 'hidden') {
                this.stopTicking();
            } else {
                this.updateAll();
                this.startTicking();
            }
        };
        document.addEventListener('visibilitychange', this.handleVisibility);
        window.addEventListener('beforeunload', () => this.destroy());

        this.citySearch?.addEventListener('input', () => this.scheduleSearch());
        this.citySearch?.addEventListener('focus', () => this.scheduleSearch());
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#city-search') && !e.target.closest('#search-results')) {
                this.searchResults.classList.add('hidden');
            }
        });
    }

    startTicking() {
        if (this.intervalId) return;
        this.intervalId = setInterval(() => this.updateAll(), 1000);
    }

    stopTicking() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    destroy() {
        this.stopTicking();
        if (this.handleVisibility) {
            document.removeEventListener('visibilitychange', this.handleVisibility);
        }
        if (this.searchAbort) this.searchAbort.abort();
    }

    updateAll() {
        const now = new Date();
        this.localTime.textContent = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        this.localDate.textContent = now.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
        this.localTimezone.textContent = Intl.DateTimeFormat().resolvedOptions().timeZone;

        this.activeCities.forEach(tz => {
            const slug = this.slugify(tz);
            const timeEl = document.getElementById(`clock-time-${slug}`);
            const dateEl = document.getElementById(`clock-date-${slug}`);
            const iconEl = document.getElementById(`clock-icon-${slug}`);

            if (timeEl) {
                timeEl.textContent = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: tz });
            }
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('pt-BR', { weekday: 'short', day: 'numeric', month: 'short', timeZone: tz });
            }
            if (iconEl) {
                const hour = parseInt(now.toLocaleTimeString('en-US', { hour: 'numeric', hour12: false, timeZone: tz }));
                const isDay = hour >= 6 && hour < 18;
                iconEl.textContent = isDay ? '☀️' : '🌙';
            }
        });
    }

    renderClocks() {
        this.clocksGrid.innerHTML = this.activeCities.map(tz => {
            const meta = this.cityMeta[tz] || { name: tz, country: '', flag: '🌍' };
            const slug = this.slugify(tz);
            const diffStr = this.formatOffsetDiff(tz);

            return `<div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 hover:border-emerald-500/30 transition-all group" id="clock-card-${escapeAttr(slug)}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">${escapeHtml(meta.flag)}</span>
                        <div>
                            <span class="text-white font-medium text-sm">${escapeHtml(meta.name)}</span>
                            <span class="text-gray-500 text-xs block">${escapeHtml(meta.country)}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="clock-icon-${escapeAttr(slug)}" class="text-sm"></span>
                        <button class="remove-clock opacity-0 group-hover:opacity-100 text-gray-500 hover:text-red-400 transition-all" data-tz="${escapeAttr(tz)}" aria-label="Remover ${escapeAttr(meta.name)}">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span id="clock-time-${escapeAttr(slug)}" class="text-2xl font-bold text-white font-mono">--:--:--</span>
                    <div class="text-right">
                        <span id="clock-date-${escapeAttr(slug)}" class="text-gray-400 text-xs block"></span>
                        <span class="text-gray-500 text-xs">${escapeHtml(diffStr)}</span>
                    </div>
                </div>
            </div>`;
        }).join('');

        refreshIcons(this.clocksGrid);

        this.clocksGrid.querySelectorAll('.remove-clock').forEach(btn => {
            btn.addEventListener('click', () => this.removeClock(btn.dataset.tz));
        });
    }

    tzOffsetMinutes(tz) {
        const parts = new Intl.DateTimeFormat('en-US', {
            timeZone: tz,
            timeZoneName: 'shortOffset',
        }).formatToParts(new Date());
        const raw = parts.find(p => p.type === 'timeZoneName')?.value ?? 'GMT';
        const m = raw.match(/GMT([+-])(\d{1,2})(?::?(\d{2}))?/);
        if (!m) return 0;
        const sign = m[1] === '-' ? -1 : 1;
        const hours = parseInt(m[2], 10);
        const mins = m[3] ? parseInt(m[3], 10) : 0;
        return sign * (hours * 60 + mins);
    }

    formatOffsetDiff(tz) {
        const localTz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const diffMinutes = this.tzOffsetMinutes(tz) - this.tzOffsetMinutes(localTz);
        const sign = diffMinutes >= 0 ? '+' : '-';
        const abs = Math.abs(diffMinutes);
        const h = Math.floor(abs / 60);
        const m = abs % 60;
        return m === 0 ? `${sign}${h}h` : `${sign}${h}h${String(m).padStart(2, '0')}`;
    }

    scheduleSearch() {
        clearTimeout(this.searchTimer);
        const q = this.citySearch.value.trim();
        if (!q) {
            this.searchResults.classList.add('hidden');
            return;
        }
        // Always show local popular-cities match immediately for snappy feedback.
        this.renderResults(this.searchPopular(q), { remote: false });
        if (q.length >= 2) {
            this.searchTimer = setTimeout(() => this.searchRemote(q), SEARCH_DEBOUNCE_MS);
        }
    }

    searchPopular(query) {
        const q = query.toLowerCase();
        return POPULAR_CITIES
            .filter(c =>
                !this.activeCities.includes(c.tz) &&
                (c.name.toLowerCase().includes(q) || c.country.toLowerCase().includes(q))
            )
            .slice(0, 5)
            .map(c => ({ name: c.name, country: c.country, flag: c.flag, tz: c.tz, source: 'popular' }));
    }

    async searchRemote(query) {
        if (this.searchAbort) this.searchAbort.abort();
        this.searchAbort = new AbortController();
        try {
            const url = `${this.searchUrl}?query=${encodeURIComponent(query)}`;
            const data = await getJson(url, { signal: this.searchAbort.signal });
            const results = (data.results || []).map(r => ({
                name: r.name,
                country: r.country,
                country_code: r.country_code,
                lat: r.lat,
                lon: r.lon,
                flag: countryCodeToFlag(r.country_code),
                source: 'remote',
            }));
            const popular = this.searchPopular(query);
            // De-dup remote against popular by name+country.
            const seen = new Set(popular.map(p => `${p.name}|${p.country}`.toLowerCase()));
            const merged = [
                ...popular,
                ...results.filter(r => !seen.has(`${r.name}|${r.country}`.toLowerCase())),
            ].slice(0, 10);
            this.renderResults(merged, { remote: true });
        } catch (err) {
            if (err.name === 'AbortError') return;
            if (err instanceof ApiError && err.status === 429) {
                showToast('Muitas buscas, tente em alguns segundos', { variant: 'error' });
            } else {
                showToast('Busca remota indisponível, mostrando lista local', { variant: 'info' });
                console.error('World clock search failed:', err);
            }
        }
    }

    renderResults(results, { remote }) {
        if (results.length === 0) {
            this.searchResults.classList.add('hidden');
            return;
        }

        this.searchResults.innerHTML = results.map((r, idx) => {
            const subtitle = r.source === 'remote'
                ? `${escapeHtml(r.country || '')}`
                : `${escapeHtml(r.country)}`;
            return `<button class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-neutral-700 transition-colors text-sm" data-idx="${idx}">
                <span class="text-lg">${escapeHtml(r.flag)}</span>
                <div class="min-w-0">
                    <span class="text-white block truncate">${escapeHtml(r.name)}</span>
                    <span class="text-gray-500 text-xs block truncate">${subtitle}</span>
                </div>
            </button>`;
        }).join('');

        this.searchResults.classList.remove('hidden');

        this.searchResults.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', async () => {
                const idx = parseInt(btn.dataset.idx, 10);
                const chosen = results[idx];
                await this.addFromResult(chosen);
                this.citySearch.value = '';
                this.searchResults.classList.add('hidden');
            });
        });
    }

    async addFromResult(result) {
        let tz;
        if (result.source === 'popular') {
            tz = result.tz;
        } else {
            try {
                const { default: tzlookup } = await import('tz-lookup');
                tz = tzlookup(result.lat, result.lon);
            } catch (err) {
                showToast('Não foi possível identificar o fuso dessa cidade', { variant: 'error' });
                console.error('tz-lookup failed:', err);
                return;
            }
        }
        if (this.activeCities.includes(tz)) {
            showToast('Esse fuso já está na lista', { variant: 'info' });
            return;
        }
        this.cityMeta[tz] = {
            name: result.name,
            country: result.country || '',
            flag: result.flag || '🌍',
        };
        this.activeCities.push(tz);
        this.saveState();
        this.renderClocks();
        this.updateAll();
    }

    removeClock(tz) {
        this.activeCities = this.activeCities.filter(t => t !== tz);
        delete this.cityMeta[tz];
        this.saveState();
        this.renderClocks();
        this.updateAll();
    }

    slugify(str) {
        return str.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="world-clock"]');
    if (root) new WorldClock(root);
});
