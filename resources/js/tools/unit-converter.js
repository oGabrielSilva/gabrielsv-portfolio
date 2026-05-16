// All units are computed relative to pixels with the user-controlled
// base font-size (root font-size). Defaults:
//   1 rem = base px
//   1 em  = base px (assuming parent is root; same as rem)
//   100%  = base px
//   1 pt  = 96/72 px  (CSS spec)
//   1 cm  = 96/2.54 px (CSS spec)
const PT_PER_PX = 72 / 96;
const CM_PER_PX = 2.54 / 96;

function pxFrom(unit, value, base) {
    const v = Number(value);
    if (!Number.isFinite(v)) return null;
    switch (unit) {
        case 'px': return v;
        case 'rem': return v * base;
        case 'em': return v * base;
        case 'percent': return (v / 100) * base;
        case 'pt': return v / PT_PER_PX;
        case 'cm': return v / CM_PER_PX;
        default: return null;
    }
}

function pxTo(unit, px, base) {
    switch (unit) {
        case 'px': return px;
        case 'rem': return px / base;
        case 'em': return px / base;
        case 'percent': return (px / base) * 100;
        case 'pt': return px * PT_PER_PX;
        case 'cm': return px * CM_PER_PX;
        default: return px;
    }
}

function format(n) {
    if (!Number.isFinite(n)) return '';
    // Strip trailing zeros, keep up to 4 decimals.
    return Number(n.toFixed(4)).toString();
}

class UnitConverter {
    constructor(root) {
        this.root = root;
        this.baseInput = document.getElementById('base-size');
        this.unitInputs = Array.from(root.querySelectorAll('.unit-input'));

        this.unitInputs.forEach(input => {
            input.addEventListener('input', () => this.handleChange(input));
        });
        this.baseInput.addEventListener('input', () => this.recomputeFromPx());

        // Seed with 16px so the user sees an example on load.
        const pxInput = this.unitInputs.find(i => i.dataset.unit === 'px');
        if (pxInput) {
            pxInput.value = '16';
            this.handleChange(pxInput);
        }
    }

    getBase() {
        const b = Number(this.baseInput.value);
        return Number.isFinite(b) && b > 0 ? b : 16;
    }

    handleChange(sourceInput) {
        const base = this.getBase();
        const px = pxFrom(sourceInput.dataset.unit, sourceInput.value, base);
        this.lastPx = px;
        if (px === null) {
            this.unitInputs.forEach(i => { if (i !== sourceInput) i.value = ''; });
            return;
        }
        this.unitInputs.forEach(input => {
            if (input === sourceInput) return;
            input.value = format(pxTo(input.dataset.unit, px, base));
        });
    }

    recomputeFromPx() {
        if (this.lastPx == null) return;
        const base = this.getBase();
        this.unitInputs.forEach(input => {
            input.value = format(pxTo(input.dataset.unit, this.lastPx, base));
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-tool="unit-converter"]');
    if (root) new UnitConverter(root);
});
