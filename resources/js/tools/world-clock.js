class WorldClock {
    constructor() {
        this.localTime = document.getElementById('local-time');
        this.localDate = document.getElementById('local-date');
        this.localTimezone = document.getElementById('local-timezone');
        this.clocksGrid = document.getElementById('clocks-grid');
        this.citySearch = document.getElementById('city-search');
        this.searchResults = document.getElementById('search-results');

        this.CITIES = [
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

        // Default shown cities
        this.activeCities = this.loadSavedCities() || [
            'America/Sao_Paulo', 'America/New_York', 'Europe/London',
            'Asia/Tokyo', 'Australia/Sydney', 'Asia/Dubai'
        ];

        this.init();
    }

    init() {
        this.renderClocks();
        this.updateAll();
        setInterval(() => this.updateAll(), 1000);

        this.citySearch?.addEventListener('input', () => this.search());
        this.citySearch?.addEventListener('focus', () => this.search());
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#city-search') && !e.target.closest('#search-results')) {
                this.searchResults.classList.add('hidden');
            }
        });
    }

    updateAll() {
        const now = new Date();

        // Local clock
        this.localTime.textContent = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        this.localDate.textContent = now.toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
        this.localTimezone.textContent = Intl.DateTimeFormat().resolvedOptions().timeZone;

        // Update each clock card
        this.activeCities.forEach(tz => {
            const timeEl = document.getElementById(`clock-time-${this.slugify(tz)}`);
            const dateEl = document.getElementById(`clock-date-${this.slugify(tz)}`);
            const iconEl = document.getElementById(`clock-icon-${this.slugify(tz)}`);

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
            const city = this.CITIES.find(c => c.tz === tz);
            if (!city) return '';
            const slug = this.slugify(tz);
            const now = new Date();
            const localOffset = now.getTimezoneOffset();
            const targetOffset = this.getTimezoneOffset(tz);
            const diffHours = (targetOffset + localOffset) / 60;
            const diffStr = diffHours >= 0 ? `+${diffHours}h` : `${diffHours}h`;

            return `<div class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4 hover:border-emerald-500/30 transition-all group" id="clock-card-${slug}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">${city.flag}</span>
                        <div>
                            <span class="text-white font-medium text-sm">${city.name}</span>
                            <span class="text-gray-500 text-xs block">${city.country}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="clock-icon-${slug}" class="text-sm"></span>
                        <button class="remove-clock opacity-0 group-hover:opacity-100 text-gray-500 hover:text-red-400 transition-all" data-tz="${tz}">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span id="clock-time-${slug}" class="text-2xl font-bold text-white font-mono">--:--:--</span>
                    <div class="text-right">
                        <span id="clock-date-${slug}" class="text-gray-400 text-xs block"></span>
                        <span class="text-gray-500 text-xs">${diffStr}</span>
                    </div>
                </div>
            </div>`;
        }).join('');

        // Re-init lucide icons for new elements
        if (window.lucide) window.lucide.createIcons();

        // Bind remove buttons
        this.clocksGrid.querySelectorAll('.remove-clock').forEach(btn => {
            btn.addEventListener('click', () => {
                this.removeClock(btn.dataset.tz);
            });
        });
    }

    getTimezoneOffset(tz) {
        const now = new Date();
        const utc = new Date(now.toLocaleString('en-US', { timeZone: 'UTC' }));
        const target = new Date(now.toLocaleString('en-US', { timeZone: tz }));
        return (target - utc) / 60000;
    }

    search() {
        const query = this.citySearch.value.trim().toLowerCase();
        if (!query) {
            this.searchResults.classList.add('hidden');
            return;
        }

        const results = this.CITIES.filter(c =>
            !this.activeCities.includes(c.tz) &&
            (c.name.toLowerCase().includes(query) || c.country.toLowerCase().includes(query))
        ).slice(0, 8);

        if (results.length === 0) {
            this.searchResults.classList.add('hidden');
            return;
        }

        this.searchResults.innerHTML = results.map(c =>
            `<button class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-neutral-700 transition-colors text-sm" data-tz="${c.tz}">
                <span class="text-lg">${c.flag}</span>
                <div>
                    <span class="text-white">${c.name}</span>
                    <span class="text-gray-500 text-xs block">${c.country}</span>
                </div>
            </button>`
        ).join('');

        this.searchResults.classList.remove('hidden');

        this.searchResults.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                this.addClock(btn.dataset.tz);
                this.citySearch.value = '';
                this.searchResults.classList.add('hidden');
            });
        });
    }

    addClock(tz) {
        if (this.activeCities.includes(tz)) return;
        this.activeCities.push(tz);
        this.saveCities();
        this.renderClocks();
        this.updateAll();
    }

    removeClock(tz) {
        this.activeCities = this.activeCities.filter(t => t !== tz);
        this.saveCities();
        this.renderClocks();
        this.updateAll();
    }

    saveCities() {
        localStorage.setItem('world-clock-cities', JSON.stringify(this.activeCities));
    }

    loadSavedCities() {
        try {
            const saved = localStorage.getItem('world-clock-cities');
            return saved ? JSON.parse(saved) : null;
        } catch {
            return null;
        }
    }

    slugify(str) {
        return str.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WorldClock();
});
