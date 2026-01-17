class PercentageCalculator {
    constructor() {
        // Calculadora 1: Porcentagem de um valor
        this.calc1 = {
            percentInput: document.getElementById("calc1-percent"),
            valueInput: document.getElementById("calc1-value"),
            resultEl: document.getElementById("calc1-result"),
            containerEl: document.getElementById("calc1-container"),
        };

        // Calculadora 2: Qual a porcentagem
        this.calc2 = {
            partInput: document.getElementById("calc2-part"),
            totalInput: document.getElementById("calc2-total"),
            resultEl: document.getElementById("calc2-result"),
            containerEl: document.getElementById("calc2-container"),
        };

        // Calculadora 3: Aumento percentual
        this.calc3 = {
            valueInput: document.getElementById("calc3-value"),
            percentInput: document.getElementById("calc3-percent"),
            resultEl: document.getElementById("calc3-result"),
            diffEl: document.getElementById("calc3-diff"),
            containerEl: document.getElementById("calc3-container"),
        };

        // Calculadora 4: Desconto percentual
        this.calc4 = {
            valueInput: document.getElementById("calc4-value"),
            percentInput: document.getElementById("calc4-percent"),
            resultEl: document.getElementById("calc4-result"),
            diffEl: document.getElementById("calc4-diff"),
            containerEl: document.getElementById("calc4-container"),
        };

        // Calculadora 5: Variação percentual
        this.calc5 = {
            fromInput: document.getElementById("calc5-from"),
            toInput: document.getElementById("calc5-to"),
            resultEl: document.getElementById("calc5-result"),
            containerEl: document.getElementById("calc5-container"),
        };

        this.init();
    }

    init() {
        // Calc 1 listeners
        this.calc1.percentInput?.addEventListener("input", () =>
            this.calculate1(),
        );
        this.calc1.valueInput?.addEventListener("input", () =>
            this.calculate1(),
        );

        // Calc 2 listeners
        this.calc2.partInput?.addEventListener("input", () =>
            this.calculate2(),
        );
        this.calc2.totalInput?.addEventListener("input", () =>
            this.calculate2(),
        );

        // Calc 3 listeners
        this.calc3.valueInput?.addEventListener("input", () =>
            this.calculate3(),
        );
        this.calc3.percentInput?.addEventListener("input", () =>
            this.calculate3(),
        );

        // Calc 4 listeners
        this.calc4.valueInput?.addEventListener("input", () =>
            this.calculate4(),
        );
        this.calc4.percentInput?.addEventListener("input", () =>
            this.calculate4(),
        );

        // Calc 5 listeners
        this.calc5.fromInput?.addEventListener("input", () =>
            this.calculate5(),
        );
        this.calc5.toInput?.addEventListener("input", () => this.calculate5());
    }

    formatNumber(num) {
        if (num === null || num === undefined || isNaN(num)) return "";
        return new Intl.NumberFormat("pt-BR", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        }).format(num);
    }

    calculate1() {
        const percent = parseFloat(this.calc1.percentInput.value);
        const value = parseFloat(this.calc1.valueInput.value);

        if (!isNaN(percent) && !isNaN(value)) {
            const result = (percent / 100) * value;
            this.calc1.resultEl.textContent = this.formatNumber(result);
        } else {
            this.calc1.resultEl.textContent = "--";
        }
    }

    calculate2() {
        const part = parseFloat(this.calc2.partInput.value);
        const total = parseFloat(this.calc2.totalInput.value);

        if (!isNaN(part) && !isNaN(total) && total !== 0) {
            const result = (part / total) * 100;
            this.calc2.resultEl.textContent = this.formatNumber(result) + "%";
        } else {
            this.calc2.resultEl.textContent = "--";
        }
    }

    calculate3() {
        const value = parseFloat(this.calc3.valueInput.value);
        const percent = parseFloat(this.calc3.percentInput.value);

        if (!isNaN(value) && !isNaN(percent)) {
            const diff = (percent / 100) * value;
            const result = value + diff;
            this.calc3.resultEl.textContent = this.formatNumber(result);
            this.calc3.diffEl.textContent =
                "(+" + this.formatNumber(diff) + ")";
        } else {
            this.calc3.resultEl.textContent = "--";
            this.calc3.diffEl.textContent = "";
        }
    }

    calculate4() {
        const value = parseFloat(this.calc4.valueInput.value);
        const percent = parseFloat(this.calc4.percentInput.value);

        if (!isNaN(value) && !isNaN(percent)) {
            const diff = (percent / 100) * value;
            const result = value - diff;
            this.calc4.resultEl.textContent = this.formatNumber(result);
            this.calc4.diffEl.textContent =
                "(-" + this.formatNumber(diff) + ")";
        } else {
            this.calc4.resultEl.textContent = "--";
            this.calc4.diffEl.textContent = "";
        }
    }

    calculate5() {
        const from = parseFloat(this.calc5.fromInput.value);
        const to = parseFloat(this.calc5.toInput.value);

        if (!isNaN(from) && !isNaN(to) && from !== 0) {
            const result = ((to - from) / from) * 100;
            const formatted =
                (result >= 0 ? "+" : "") + this.formatNumber(result) + "%";
            this.calc5.resultEl.textContent = formatted;

            // Atualiza a cor baseado no resultado
            if (result >= 0) {
                this.calc5.resultEl.classList.remove("text-red-400");
                this.calc5.resultEl.classList.add("text-emerald-400");
            } else {
                this.calc5.resultEl.classList.remove("text-emerald-400");
                this.calc5.resultEl.classList.add("text-red-400");
            }
        } else {
            this.calc5.resultEl.textContent = "--";
            this.calc5.resultEl.classList.remove("text-red-400");
            this.calc5.resultEl.classList.add("text-emerald-400");
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new PercentageCalculator();
});
