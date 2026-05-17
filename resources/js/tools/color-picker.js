import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

const clamp = (n, min, max) => Math.min(max, Math.max(min, n));

class ColorPicker {
    constructor() {
        this.colorPicker = document.getElementById('color-picker');
        this.colorPreview = document.getElementById('color-preview');
        this.hexInput = document.getElementById('hex-input');
        this.rgbInput = document.getElementById('rgb-input');
        this.hslInput = document.getElementById('hsl-input');
        this.hsbInput = document.getElementById('hsb-input');
        this.palComp = document.getElementById('palette-complementary');
        this.palAnalog = document.getElementById('palette-analogous');
        this.palTriadic = document.getElementById('palette-triadic');
        this.palTetradic = document.getElementById('palette-tetradic');
        this.tintsShades = document.getElementById('tints-shades');
        this.paletteWarning = document.getElementById('palette-warning');

        // Current color in RGBA
        this.r = 0; this.g = 209; this.b = 178; this.a = 255;

        this.isUpdating = false;

        this.init();
    }

    init() {
        this.colorPicker?.addEventListener('input', (e) => {
            this.a = 255;
            this.setFromHex(e.target.value);
        });

        this.hexInput?.addEventListener('change', () => {
            const raw = this.hexInput.value.trim().replace(/^#/, '');
            if (/^([0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/.test(raw)) {
                this.setFromHex('#' + raw);
            } else {
                showToast('HEX inválido. Use #RGB, #RRGGBB ou #RRGGBBAA', { variant: 'error' });
                this.hexInput.value = this.currentHex();
            }
        });

        this.rgbInput?.addEventListener('change', () => {
            const m = this.rgbInput.value.match(/(\d+)\s*,\s*(\d+)\s*,\s*(\d+)(?:\s*,\s*([\d.]+))?/);
            if (m) {
                this.r = clamp(parseInt(m[1], 10), 0, 255);
                this.g = clamp(parseInt(m[2], 10), 0, 255);
                this.b = clamp(parseInt(m[3], 10), 0, 255);
                this.a = m[4] !== undefined ? clamp(Math.round(parseFloat(m[4]) * 255), 0, 255) : 255;
                this.updateAll();
            }
        });

        this.hslInput?.addEventListener('change', () => {
            const m = this.hslInput.value.match(/(\d+)\s*,\s*(\d+)%?\s*,\s*(\d+)%?/);
            if (m) {
                const h = clamp(parseInt(m[1], 10), 0, 360);
                const s = clamp(parseInt(m[2], 10), 0, 100);
                const l = clamp(parseInt(m[3], 10), 0, 100);
                const [r, g, b] = this.hslToRgb(h, s, l);
                this.r = r; this.g = g; this.b = b; this.a = 255;
                this.updateAll();
            }
        });

        this.hsbInput?.addEventListener('change', () => {
            const m = this.hsbInput.value.match(/(\d+)\s*,\s*(\d+)%?\s*,\s*(\d+)%?/);
            if (m) {
                const h = clamp(parseInt(m[1], 10), 0, 360);
                const s = clamp(parseInt(m[2], 10), 0, 100);
                const v = clamp(parseInt(m[3], 10), 0, 100);
                const [r, g, b] = this.hsbToRgb(h, s, v);
                this.r = r; this.g = g; this.b = b; this.a = 255;
                this.updateAll();
            }
        });

        document.querySelectorAll('.copy-val').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                if (input) this.copy(input.value);
            });
        });

        this.updateAll();
    }

    setFromHex(hex) {
        let h = hex.replace('#', '');
        if (h.length === 3) {
            h = h.split('').map(c => c + c).join('');
        }
        this.r = parseInt(h.substring(0, 2), 16);
        this.g = parseInt(h.substring(2, 4), 16);
        this.b = parseInt(h.substring(4, 6), 16);
        if (h.length === 8) {
            this.a = parseInt(h.substring(6, 8), 16);
        } else {
            this.a = 255;
        }
        this.updateAll();
    }

    currentHex() {
        const hex6 = this.rgbToHex(this.r, this.g, this.b);
        if (this.a < 255) {
            return hex6 + this.a.toString(16).padStart(2, '0');
        }
        return hex6;
    }

    updateAll() {
        if (this.isUpdating) return;
        this.isUpdating = true;

        const hex = this.currentHex();
        const hex6 = this.rgbToHex(this.r, this.g, this.b);
        const [h, s, l] = this.rgbToHsl(this.r, this.g, this.b);
        const [hb, sb, vb] = this.rgbToHsb(this.r, this.g, this.b);
        const alphaFloat = +(this.a / 255).toFixed(3);

        this.colorPreview.style.background = this.a < 255
            ? `rgba(${this.r}, ${this.g}, ${this.b}, ${alphaFloat})`
            : hex6;
        this.colorPicker.value = hex6;
        this.hexInput.value = hex;
        this.rgbInput.value = this.a < 255
            ? `rgba(${this.r}, ${this.g}, ${this.b}, ${alphaFloat})`
            : `rgb(${this.r}, ${this.g}, ${this.b})`;
        this.hslInput.value = `hsl(${h}, ${s}%, ${l}%)`;
        this.hsbInput.value = `hsb(${hb}, ${sb}%, ${vb}%)`;

        this.generatePalettes(h, s, l);
        this.generateTintsShades(h, s, l);

        if (this.paletteWarning) {
            this.paletteWarning.classList.toggle('hidden', s !== 0);
        }

        this.isUpdating = false;
    }

    // --- Color conversions ---

    rgbToHex(r, g, b) {
        return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
    }

    rgbToHsl(r, g, b) {
        r /= 255; g /= 255; b /= 255;
        const max = Math.max(r, g, b), min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                case g: h = ((b - r) / d + 2) / 6; break;
                case b: h = ((r - g) / d + 4) / 6; break;
            }
        }

        return [Math.round(h * 360), Math.round(s * 100), Math.round(l * 100)];
    }

    hslToRgb(h, s, l) {
        h /= 360; s /= 100; l /= 100;
        let r, g, b;

        if (s === 0) {
            r = g = b = l;
        } else {
            const hue2rgb = (p, q, t) => {
                if (t < 0) t += 1;
                if (t > 1) t -= 1;
                if (t < 1/6) return p + (q - p) * 6 * t;
                if (t < 1/2) return q;
                if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                return p;
            };
            const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
            const p = 2 * l - q;
            r = hue2rgb(p, q, h + 1/3);
            g = hue2rgb(p, q, h);
            b = hue2rgb(p, q, h - 1/3);
        }

        return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
    }

    rgbToHsb(r, g, b) {
        r /= 255; g /= 255; b /= 255;
        const max = Math.max(r, g, b), min = Math.min(r, g, b);
        const d = max - min;
        let h, s = max === 0 ? 0 : d / max, v = max;

        if (max === min) {
            h = 0;
        } else {
            switch (max) {
                case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                case g: h = ((b - r) / d + 2) / 6; break;
                case b: h = ((r - g) / d + 4) / 6; break;
            }
        }

        return [Math.round(h * 360), Math.round(s * 100), Math.round(v * 100)];
    }

    hsbToRgb(h, s, v) {
        h /= 360; s /= 100; v /= 100;
        const i = Math.floor(h * 6);
        const f = h * 6 - i;
        const p = v * (1 - s);
        const q = v * (1 - f * s);
        const t = v * (1 - (1 - f) * s);
        let r, g, b;
        switch (i % 6) {
            case 0: r = v; g = t; b = p; break;
            case 1: r = q; g = v; b = p; break;
            case 2: r = p; g = v; b = t; break;
            case 3: r = p; g = q; b = v; break;
            case 4: r = t; g = p; b = v; break;
            case 5: r = v; g = p; b = q; break;
        }
        return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
    }

    // --- Palettes ---

    generatePalettes(h, s, l) {
        const comp = [
            this.hslToHex(h, s, l),
            this.hslToHex((h + 180) % 360, s, l)
        ];
        this.renderPalette(this.palComp, comp);

        const analog = [
            this.hslToHex((h - 30 + 360) % 360, s, l),
            this.hslToHex(h, s, l),
            this.hslToHex((h + 30) % 360, s, l)
        ];
        this.renderPalette(this.palAnalog, analog);

        const triadic = [
            this.hslToHex(h, s, l),
            this.hslToHex((h + 120) % 360, s, l),
            this.hslToHex((h + 240) % 360, s, l)
        ];
        this.renderPalette(this.palTriadic, triadic);

        const tetradic = [
            this.hslToHex(h, s, l),
            this.hslToHex((h + 90) % 360, s, l),
            this.hslToHex((h + 180) % 360, s, l),
            this.hslToHex((h + 270) % 360, s, l)
        ];
        this.renderPalette(this.palTetradic, tetradic);
    }

    renderPalette(container, colors) {
        container.innerHTML = colors.map(hex =>
            `<button type="button" class="palette-swatch flex-1 rounded-lg border border-neutral-600 cursor-pointer hover:scale-105 transition-transform" style="background-color: ${hex}; appearance: none; -webkit-appearance: none;" data-hex="${hex}" title="${hex}" aria-label="Selecionar ${hex}"></button>`
        ).join('');

        container.querySelectorAll('.palette-swatch').forEach(el => {
            el.addEventListener('click', () => this.selectAndCopy(el.dataset.hex));
        });
    }

    generateTintsShades(h, s, l) {
        const colors = [];
        for (let i = 5; i >= 1; i--) {
            colors.push(this.hslToHex(h, s, Math.max(0, l - i * 10)));
        }
        colors.push(this.hslToHex(h, s, l));
        for (let i = 1; i <= 5; i++) {
            colors.push(this.hslToHex(h, s, Math.min(100, l + i * 10)));
        }

        this.tintsShades.innerHTML = colors.map((hex, idx) =>
            `<button type="button" class="palette-swatch flex-1 cursor-pointer hover:scale-y-110 transition-transform border border-neutral-700 ${idx === 5 ? 'ring-2 ring-white ring-offset-2 ring-offset-neutral-800' : ''}" style="background-color: ${hex}; appearance: none; -webkit-appearance: none;" data-hex="${hex}" title="${hex}" aria-label="Selecionar ${hex}"></button>`
        ).join('');

        this.tintsShades.querySelectorAll('.palette-swatch').forEach(el => {
            el.addEventListener('click', () => this.selectAndCopy(el.dataset.hex));
        });
    }

    hslToHex(h, s, l) {
        const [r, g, b] = this.hslToRgb(h, s, l);
        return this.rgbToHex(r, g, b);
    }

    selectAndCopy(hex) {
        this.a = 255;
        this.setFromHex(hex);
        this.copy(hex);
    }

    async copy(text) {
        try {
            await copyText(text);
            showToast('Copiado!');
        } catch (e) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', e);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ColorPicker();
});
