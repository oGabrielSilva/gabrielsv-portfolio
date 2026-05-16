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

        // Current color in RGB
        this.r = 0; this.g = 209; this.b = 178;

        this.init();
    }

    init() {
        this.colorPicker?.addEventListener('input', (e) => {
            this.setFromHex(e.target.value);
        });

        this.hexInput?.addEventListener('change', () => {
            const hex = this.hexInput.value.trim();
            if (/^#?[0-9a-fA-F]{6}$/.test(hex)) {
                this.setFromHex(hex.startsWith('#') ? hex : '#' + hex);
            }
        });

        this.rgbInput?.addEventListener('change', () => {
            const match = this.rgbInput.value.match(/(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/);
            if (match) {
                this.r = clamp(parseInt(match[1], 10), 0, 255);
                this.g = clamp(parseInt(match[2], 10), 0, 255);
                this.b = clamp(parseInt(match[3], 10), 0, 255);
                this.updateAll();
            }
        });

        this.hslInput?.addEventListener('change', () => {
            const match = this.hslInput.value.match(/(\d+)\s*,\s*(\d+)%?\s*,\s*(\d+)%?/);
            if (match) {
                const h = clamp(parseInt(match[1], 10), 0, 360);
                const s = clamp(parseInt(match[2], 10), 0, 100);
                const l = clamp(parseInt(match[3], 10), 0, 100);
                const [r, g, b] = this.hslToRgb(h, s, l);
                this.r = r; this.g = g; this.b = b;
                this.updateAll();
            }
        });

        // Copy buttons
        document.querySelectorAll('.copy-val').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                if (input) this.copy(input.value);
            });
        });

        this.updateAll();
    }

    setFromHex(hex) {
        hex = hex.replace('#', '');
        this.r = parseInt(hex.substring(0, 2), 16);
        this.g = parseInt(hex.substring(2, 4), 16);
        this.b = parseInt(hex.substring(4, 6), 16);
        this.updateAll();
    }

    updateAll() {
        const hex = this.rgbToHex(this.r, this.g, this.b);
        const [h, s, l] = this.rgbToHsl(this.r, this.g, this.b);
        const [hb, sb, vb] = this.rgbToHsb(this.r, this.g, this.b);

        this.colorPreview.style.background = hex;
        this.colorPicker.value = hex;
        this.hexInput.value = hex;
        this.rgbInput.value = `rgb(${this.r}, ${this.g}, ${this.b})`;
        this.hslInput.value = `hsl(${h}, ${s}%, ${l}%)`;
        this.hsbInput.value = `hsb(${hb}, ${sb}%, ${vb}%)`;

        this.generatePalettes(h, s, l);
        this.generateTintsShades(h, s, l);
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

    // --- Palettes ---

    generatePalettes(h, s, l) {
        // Complementary: +180°
        const comp = [
            this.hslToHex(h, s, l),
            this.hslToHex((h + 180) % 360, s, l)
        ];
        this.renderPalette(this.palComp, comp);

        // Analogous: -30°, 0°, +30°
        const analog = [
            this.hslToHex((h - 30 + 360) % 360, s, l),
            this.hslToHex(h, s, l),
            this.hslToHex((h + 30) % 360, s, l)
        ];
        this.renderPalette(this.palAnalog, analog);

        // Triadic: +120°, +240°
        const triadic = [
            this.hslToHex(h, s, l),
            this.hslToHex((h + 120) % 360, s, l),
            this.hslToHex((h + 240) % 360, s, l)
        ];
        this.renderPalette(this.palTriadic, triadic);

        // Tetradic: +90°, +180°, +270°
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
            `<button class="flex-1 rounded-lg border border-neutral-600 cursor-pointer hover:scale-105 transition-transform palette-swatch" style="background: ${hex}" data-hex="${hex}" title="${hex}"></button>`
        ).join('');

        container.querySelectorAll('.palette-swatch').forEach(el => {
            el.addEventListener('click', () => this.copy(el.dataset.hex));
        });
    }

    generateTintsShades(h, s, l) {
        const colors = [];
        // Shades (darker) → base → Tints (lighter)
        for (let i = 5; i >= 1; i--) {
            colors.push(this.hslToHex(h, s, Math.max(0, l - i * 10)));
        }
        colors.push(this.hslToHex(h, s, l)); // base
        for (let i = 1; i <= 5; i++) {
            colors.push(this.hslToHex(h, s, Math.min(100, l + i * 10)));
        }

        this.tintsShades.innerHTML = colors.map((hex, idx) =>
            `<button class="flex-1 cursor-pointer hover:scale-y-110 transition-transform palette-swatch ${idx === 5 ? 'ring-2 ring-white ring-offset-2 ring-offset-neutral-800' : ''}" style="background: ${hex}" data-hex="${hex}" title="${hex}"></button>`
        ).join('');

        this.tintsShades.querySelectorAll('.palette-swatch').forEach(el => {
            el.addEventListener('click', () => this.copy(el.dataset.hex));
        });
    }

    hslToHex(h, s, l) {
        const [r, g, b] = this.hslToRgb(h, s, l);
        return this.rgbToHex(r, g, b);
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
