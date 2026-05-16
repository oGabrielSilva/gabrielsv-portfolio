class CronExplainer {
    constructor() {
        this.cronInput = document.getElementById('cron-input');
        this.explainBtn = document.getElementById('explain-btn');
        this.explanation = document.getElementById('explanation');
        this.nextRuns = document.getElementById('next-runs');
        this.presets = document.querySelectorAll('.preset-btn');

        this.MONTHS = ['', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
        this.DAYS = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];

        this.init();
    }

    init() {
        this.explainBtn?.addEventListener('click', () => this.explain());
        this.cronInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') this.explain();
        });

        this.presets?.forEach(btn => {
            btn.addEventListener('click', () => {
                this.cronInput.value = btn.dataset.cron;
                this.explain();
            });
        });

        // Auto-explain the default value
        this.explain();
    }

    explain() {
        const expr = this.cronInput.value.trim();
        if (!expr) return;

        const parts = expr.split(/\s+/);
        if (parts.length !== 5) {
            this.explanation.textContent = 'Expressão inválida. Use 5 campos: minuto hora dia mês dia-da-semana';
            this.nextRuns.innerHTML = '';
            return;
        }

        const FIELDS = [
            { name: 'minuto', min: 0, max: 59 },
            { name: 'hora', min: 0, max: 23 },
            { name: 'dia do mês', min: 1, max: 31 },
            { name: 'mês', min: 1, max: 12 },
            { name: 'dia da semana', min: 0, max: 7 },
        ];
        for (let i = 0; i < 5; i++) {
            const err = this.validateField(parts[i], FIELDS[i].min, FIELDS[i].max);
            if (err) {
                this.explanation.textContent = `Campo "${FIELDS[i].name}" inválido: ${err}`;
                this.nextRuns.innerHTML = '';
                return;
            }
        }

        const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;

        try {
            const desc = this.describe(minute, hour, dayOfMonth, month, dayOfWeek);
            this.explanation.textContent = desc;
            this.calculateNextRuns(parts);
        } catch (e) {
            this.explanation.textContent = 'Erro ao interpretar: ' + e.message;
            this.nextRuns.innerHTML = '';
        }
    }

    validateField(field, min, max) {
        if (field === '*') return null;

        // List: each segment validated independently
        if (field.includes(',')) {
            for (const seg of field.split(',')) {
                const err = this.validateField(seg.trim(), min, max);
                if (err) return err;
            }
            return null;
        }

        // Step: range/step or */step
        if (field.includes('/')) {
            const [range, step] = field.split('/');
            const stepNum = parseInt(step, 10);
            if (!Number.isFinite(stepNum) || stepNum <= 0) return `step "${step}" deve ser maior que 0`;
            if (stepNum > max) return `step "${step}" excede o máximo (${max})`;
            if (range !== '*') {
                const rangeErr = this.validateField(range, min, max);
                if (rangeErr) return rangeErr;
            }
            return null;
        }

        // Range: a-b
        if (field.includes('-')) {
            const [s, e] = field.split('-').map(v => parseInt(v, 10));
            if (!Number.isFinite(s) || !Number.isFinite(e)) return `range "${field}" mal formado`;
            if (s < min || e > max) return `range "${field}" fora do intervalo permitido (${min}-${max})`;
            if (s > e) return `range "${field}" invertido (início > fim)`;
            return null;
        }

        // Exact value
        const n = parseInt(field, 10);
        if (!Number.isFinite(n)) return `"${field}" não é um número`;
        if (n < min || n > max) return `${n} fora do intervalo (${min}-${max})`;
        return null;
    }

    describe(minute, hour, dayOfMonth, month, dayOfWeek) {
        let parts = [];

        // Time description
        if (minute === '*' && hour === '*') {
            parts.push('A cada minuto');
        } else if (minute.startsWith('*/')) {
            parts.push(`A cada ${minute.slice(2)} minutos`);
        } else if (hour === '*' && minute !== '*') {
            parts.push(`No minuto ${minute} de cada hora`);
        } else if (minute === '0' && hour !== '*') {
            if (hour.startsWith('*/')) {
                parts.push(`A cada ${hour.slice(2)} horas`);
            } else if (hour.includes(',')) {
                parts.push(`Às ${hour.split(',').join('h, ')}h`);
            } else if (hour.includes('-')) {
                const [s, e] = hour.split('-');
                parts.push(`A cada hora, das ${s}h às ${e}h`);
            } else {
                parts.push(`Às ${hour.padStart(2, '0')}:00`);
            }
        } else if (minute !== '*' && hour !== '*') {
            parts.push(`Às ${hour.padStart(2, '0')}:${minute.padStart(2, '0')}`);
        }

        // Day of month
        if (dayOfMonth !== '*') {
            if (dayOfMonth.includes(',')) {
                parts.push(`nos dias ${dayOfMonth}`);
            } else if (dayOfMonth.includes('-')) {
                const [s, e] = dayOfMonth.split('-');
                parts.push(`do dia ${s} ao ${e}`);
            } else {
                parts.push(`no dia ${dayOfMonth}`);
            }
        }

        // Month
        if (month !== '*') {
            if (month.includes(',')) {
                const names = month.split(',').map(m => this.MONTHS[parseInt(m)] || m);
                parts.push(`em ${names.join(', ')}`);
            } else if (month.includes('-')) {
                const [s, e] = month.split('-');
                parts.push(`de ${this.MONTHS[parseInt(s)] || s} a ${this.MONTHS[parseInt(e)] || e}`);
            } else {
                parts.push(`em ${this.MONTHS[parseInt(month)] || month}`);
            }
        }

        // Day of week
        if (dayOfWeek !== '*') {
            if (dayOfWeek.includes(',')) {
                const names = dayOfWeek.split(',').map(d => this.DAYS[parseInt(d)] || d);
                parts.push(`(${names.join(', ')})`);
            } else if (dayOfWeek.includes('-')) {
                const [s, e] = dayOfWeek.split('-');
                parts.push(`(de ${this.DAYS[parseInt(s)] || s} a ${this.DAYS[parseInt(e)] || e})`);
            } else {
                parts.push(`(${this.DAYS[parseInt(dayOfWeek)] || dayOfWeek})`);
            }
        }

        return parts.join(' ') || 'A cada minuto';
    }

    calculateNextRuns(parts) {
        const runs = [];
        const now = new Date();
        let current = new Date(now);

        for (let i = 0; i < 10000 && runs.length < 7; i++) {
            current = new Date(current.getTime() + 60000); // +1 min

            if (this.matchesCron(current, parts)) {
                runs.push(new Date(current));
            }
        }

        this.nextRuns.innerHTML = runs.map((date, idx) => {
            const formatted = date.toLocaleDateString('pt-BR', {
                weekday: 'short', year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit'
            });
            return `<div class="flex items-center gap-3 py-2 ${idx < runs.length - 1 ? 'border-b border-neutral-700/50' : ''}">
                <span class="text-xs font-mono text-bulma-primary w-6">#${idx + 1}</span>
                <span class="text-gray-300 text-sm">${formatted}</span>
            </div>`;
        }).join('');

        if (runs.length === 0) {
            this.nextRuns.innerHTML = '<p class="text-gray-500 text-sm">Não foi possível calcular as próximas execuções</p>';
        }
    }

    matchesCron(date, parts) {
        const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;
        return this.matchField(date.getMinutes(), minute, 0, 59) &&
               this.matchField(date.getHours(), hour, 0, 23) &&
               this.matchField(date.getDate(), dayOfMonth, 1, 31) &&
               this.matchField(date.getMonth() + 1, month, 1, 12) &&
               this.matchField(date.getDay(), dayOfWeek, 0, 6);
    }

    matchField(value, field, min, max) {
        if (field === '*') return true;

        // Handle lists: 1,3,5
        if (field.includes(',')) {
            return field.split(',').some(f => this.matchField(value, f.trim(), min, max));
        }

        // Handle step: */5 or 1-10/2
        if (field.includes('/')) {
            const [range, step] = field.split('/');
            const stepNum = parseInt(step);
            if (range === '*') {
                return value % stepNum === 0;
            }
            if (range.includes('-')) {
                const [s, e] = range.split('-').map(Number);
                return value >= s && value <= e && (value - s) % stepNum === 0;
            }
            return value % stepNum === 0;
        }

        // Handle range: 1-5
        if (field.includes('-')) {
            const [s, e] = field.split('-').map(Number);
            return value >= s && value <= e;
        }

        // Exact match
        return value === parseInt(field);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new CronExplainer();
});
