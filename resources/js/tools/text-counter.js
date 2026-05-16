// Average words-per-minute used to estimate reading time. 200 wpm is the
// commonly cited PT-BR adult average for moderately complex text.
const WORDS_PER_MINUTE = 200;

class TextCounter {
    constructor(root) {
        this.root = root;
        this.input = document.getElementById('text-input');
        this.statChars = document.getElementById('stat-chars');
        this.statCharsNoSpace = document.getElementById('stat-chars-nospace');
        this.statWords = document.getElementById('stat-words');
        this.statLines = document.getElementById('stat-lines');
        this.statParagraphs = document.getElementById('stat-paragraphs');
        this.statReading = document.getElementById('stat-reading');
        this.limitRows = this.root.querySelectorAll('.limit-row');

        this.input.addEventListener('input', () => this.update());
        this.update();
    }

    update() {
        const text = this.input.value;
        const chars = text.length;
        const charsNoSpace = text.replace(/\s/g, '').length;
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const lines = text === '' ? 0 : text.split('\n').length;
        const paragraphs = text.split(/\n\s*\n/).filter(p => p.trim()).length;

        this.statChars.textContent = chars.toLocaleString('pt-BR');
        this.statCharsNoSpace.textContent = charsNoSpace.toLocaleString('pt-BR');
        this.statWords.textContent = words.toLocaleString('pt-BR');
        this.statLines.textContent = lines.toLocaleString('pt-BR');
        this.statParagraphs.textContent = paragraphs.toLocaleString('pt-BR');
        this.statReading.textContent = this.formatReadingTime(words);

        this.limitRows.forEach(row => {
            const limit = parseInt(row.dataset.limit, 10);
            const current = chars;
            const pct = Math.min(100, (current / limit) * 100);
            const bar = row.querySelector('.limit-bar');
            const currentEl = row.querySelector('.limit-current');
            currentEl.textContent = current.toLocaleString('pt-BR');
            bar.style.width = pct + '%';
            bar.classList.remove('bg-bulma-primary', 'bg-amber-500', 'bg-red-500');
            if (current > limit) {
                bar.classList.add('bg-red-500');
            } else if (current > limit * 0.9) {
                bar.classList.add('bg-amber-500');
            } else {
                bar.classList.add('bg-bulma-primary');
            }
        });
    }

    formatReadingTime(words) {
        if (words === 0) return '0 s';
        const seconds = Math.ceil((words / WORDS_PER_MINUTE) * 60);
        if (seconds < 60) return `${seconds} s`;
        const minutes = Math.floor(seconds / 60);
        const rest = seconds % 60;
        return rest === 0 ? `${minutes} min` : `${minutes}m${String(rest).padStart(2, '0')}`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="text-counter"]');
    if (root) new TextCounter(root);
});
