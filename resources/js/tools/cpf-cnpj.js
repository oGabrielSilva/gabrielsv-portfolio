import { cnpj as CNPJ, cpf as CPF } from "cpf-cnpj-validator";

class CpfCnpjTool {
    constructor() {
        this.currentType = window.cpfCnpjConfig?.type || "cpf";
        this.urls = window.cpfCnpjConfig?.urls || {};
        this.elements = {};
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.generate();
    }

    cacheElements() {
        this.elements = {
            tabs: document.querySelectorAll(".tab-btn"),
            typeLabel: document.getElementById("type-label"),
            validateTypeLabel: document.getElementById("validate-type-label"),
            inputTypeLabel: document.getElementById("input-type-label"),
            quantity: document.getElementById("quantity"),
            formatted: document.getElementById("formatted"),
            generateBtn: document.getElementById("generate-btn"),
            resultsList: document.getElementById("results-list"),
            copyAllBtn: document.getElementById("copy-all-btn"),
            validateInput: document.getElementById("validate-input"),
            validateBtn: document.getElementById("validate-btn"),
            validationResult: document.getElementById("validation-result"),
            validationMessage: document.getElementById("validation-message"),
            toast: document.getElementById("toast"),
            toastMessage: document.getElementById("toast-message"),
        };
    }

    bindEvents() {
        // Tabs
        this.elements.tabs.forEach((tab) => {
            tab.addEventListener("click", () => this.switchTab(tab.dataset.tab));
        });

        // Generate
        this.elements.generateBtn.addEventListener("click", () =>
            this.generate(),
        );

        // Copy all
        this.elements.copyAllBtn.addEventListener("click", () =>
            this.copyAll(),
        );

        // Individual copy (delegated)
        this.elements.resultsList.addEventListener("click", (e) => {
            const copyBtn = e.target.closest(".copy-btn");
            if (copyBtn) {
                this.copy(copyBtn.dataset.value);
            }
        });

        // Validate
        this.elements.validateBtn.addEventListener("click", () =>
            this.validate(),
        );
        this.elements.validateInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") this.validate();
        });

        // Auto-format input
        this.elements.validateInput.addEventListener("input", (e) =>
            this.formatInput(e),
        );
    }

    switchTab(type) {
        if (type === this.currentType) return;

        this.currentType = type;

        // Update tab styles
        this.elements.tabs.forEach((tab) => {
            const isActive = tab.dataset.tab === type;
            tab.classList.toggle("border-bulma-primary", isActive);
            tab.classList.toggle("text-bulma-primary", isActive);
            tab.classList.toggle("border-transparent", !isActive);
            tab.classList.toggle("text-gray-400", !isActive);
        });

        // Update labels
        const label = type.toUpperCase();
        this.elements.typeLabel.textContent = label;
        this.elements.validateTypeLabel.textContent = label;
        this.elements.inputTypeLabel.textContent = label;

        // Update placeholder
        this.elements.validateInput.placeholder =
            type === "cpf" ? "000.000.000-00" : "00.000.000/0000-00";

        // Clear validation
        this.elements.validationResult.classList.add("hidden");
        this.elements.validateInput.value = "";

        // Update URL without reload
        if (this.urls[type]) {
            history.pushState({ type }, "", this.urls[type]);
        }

        // Generate new values
        this.generate();
    }

    generate() {
        const quantity = parseInt(this.elements.quantity.value) || 5;
        const formatted = this.elements.formatted.checked;
        const results = [];

        const Generator = this.currentType === "cpf" ? CPF : CNPJ;

        for (let i = 0; i < quantity; i++) {
            let value = Generator.generate();
            if (formatted) {
                value = Generator.format(value);
            }
            results.push(value);
        }

        this.renderResults(results);
    }

    renderResults(results) {
        this.elements.resultsList.innerHTML = results
            .map(
                (value) => `
            <div class="flex items-center gap-2 group">
                <code class="flex-1 py-2 px-3 bg-neutral-900 rounded-lg text-xs sm:text-sm text-gray-300 font-mono break-all result-item">${value}</code>
                <button type="button"
                    class="copy-btn p-2 text-gray-500 hover:text-bulma-primary transition-colors"
                    data-value="${value}" title="Copiar">
                    <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
            </div>
        `,
            )
            .join("");

        // Re-init Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    validate() {
        const input = this.elements.validateInput.value.trim();
        if (!input) return;

        const Validator = this.currentType === "cpf" ? CPF : CNPJ;
        const isValid = Validator.isValid(input);

        this.elements.validationResult.classList.remove("hidden");
        this.elements.validationMessage.className = `py-3 px-4 rounded-lg flex items-center gap-2 text-sm font-medium ${
            isValid
                ? "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"
                : "bg-red-500/10 text-red-400 border border-red-500/20"
        }`;

        const icon = isValid ? "check-circle" : "x-circle";
        const message = isValid
            ? `${this.currentType.toUpperCase()} válido!`
            : `${this.currentType.toUpperCase()} inválido`;

        this.elements.validationMessage.innerHTML = `
            <i data-lucide="${icon}" class="w-4 h-4"></i>
            ${message}
        `;

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    formatInput(e) {
        let value = e.target.value.replace(/\D/g, "");

        if (this.currentType === "cpf") {
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length > 9) {
                value = value.replace(
                    /(\d{3})(\d{3})(\d{3})(\d{1,2})/,
                    "$1.$2.$3-$4",
                );
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, "$1.$2.$3");
            } else if (value.length > 3) {
                value = value.replace(/(\d{3})(\d{1,3})/, "$1.$2");
            }
        } else {
            if (value.length > 14) value = value.slice(0, 14);
            if (value.length > 12) {
                value = value.replace(
                    /(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/,
                    "$1.$2.$3/$4-$5",
                );
            } else if (value.length > 8) {
                value = value.replace(
                    /(\d{2})(\d{3})(\d{3})(\d{1,4})/,
                    "$1.$2.$3/$4",
                );
            } else if (value.length > 5) {
                value = value.replace(/(\d{2})(\d{3})(\d{1,3})/, "$1.$2.$3");
            } else if (value.length > 2) {
                value = value.replace(/(\d{2})(\d{1,3})/, "$1.$2");
            }
        }

        e.target.value = value;
    }

    async copy(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showToast("Copiado!");
        } catch (err) {
            console.error("Erro ao copiar:", err);
        }
    }

    async copyAll() {
        const items =
            this.elements.resultsList.querySelectorAll(".result-item");
        const values = Array.from(items)
            .map((el) => el.textContent)
            .join("\n");

        try {
            await navigator.clipboard.writeText(values);
            this.showToast("Todos copiados!");
        } catch (err) {
            console.error("Erro ao copiar:", err);
        }
    }

    showToast(message) {
        this.elements.toastMessage.textContent = message;
        this.elements.toast.classList.remove("opacity-0", "translate-y-2");
        this.elements.toast.classList.add("opacity-100", "translate-y-0");

        setTimeout(() => {
            this.elements.toast.classList.add("opacity-0", "translate-y-2");
            this.elements.toast.classList.remove(
                "opacity-100",
                "translate-y-0",
            );
        }, 2000);
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    const tool = new CpfCnpjTool();
    tool.init();
});
