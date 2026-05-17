import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

class SlugifyTool {
    constructor() {
        this.separator = "-";
        this.caseFormat = "lowercase";
        this.elements = {};
    }

    init() {
        this.cacheElements();
        this.bindEvents();
    }

    cacheElements() {
        this.elements = {
            textInput: document.getElementById("text-input"),
            separator: document.getElementById("separator"),
            separatorLabel: document.getElementById("separator-label"),
            separatorOptions: document.querySelectorAll(".separator-option"),
            caseFormat: document.getElementById("case-format"),
            caseLabel: document.getElementById("case-label"),
            caseOptions: document.querySelectorAll(".case-option"),
            slugOutput: document.getElementById("slug-output"),
            copyBtn: document.getElementById("copy-btn"),
            charCount: document.getElementById("char-count"),
            slugLength: document.getElementById("slug-length"),
            exampleBtns: document.querySelectorAll(".example-btn"),
        };
    }

    bindEvents() {
        // Real-time conversion
        this.elements.textInput.addEventListener("input", () => this.convert());

        // Separator dropdown options
        this.elements.separatorOptions.forEach((option) => {
            option.addEventListener("click", () => {
                this.separator = option.dataset.value;
                this.elements.separator.value = this.separator;
                this.elements.separatorLabel.textContent = option.dataset.label;
                this.closeDropdown(option);
                this.convert();
            });
        });

        // Case format dropdown options
        this.elements.caseOptions.forEach((option) => {
            option.addEventListener("click", () => {
                this.caseFormat = option.dataset.value;
                this.elements.caseFormat.value = this.caseFormat;
                this.elements.caseLabel.textContent = option.dataset.label;
                this.closeDropdown(option);
                this.convert();
            });
        });

        // Copy button
        this.elements.copyBtn.addEventListener("click", () =>
            this.copy(this.elements.slugOutput.value),
        );

        // Example buttons
        this.elements.exampleBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                this.elements.textInput.value = btn.dataset.text;
                this.convert();
            });
        });
    }

    closeDropdown(option) {
        const dropdown = option.closest(".hs-dropdown");
        if (dropdown) {
            window.HSDropdown?.close(dropdown);
        }
    }

    convert() {
        const text = this.elements.textInput.value;
        const slug = this.slugify(text, this.separator, this.caseFormat);

        this.elements.slugOutput.value = slug;
        this.elements.charCount.textContent = text.length;
        this.elements.slugLength.textContent = slug.length;
    }

    slugify(text, separator = "-", caseFormat = "lowercase") {
        if (!text) return "";

        let slug = text
            // Normalize unicode characters (NFD decomposes accented chars)
            .normalize("NFD")
            // Remove diacritical marks (accents)
            .replace(/[\u0300-\u036f]/g, "")
            // Replace spaces and common separators with space temporarily
            .replace(/[\s_-]+/g, " ")
            // Remove special characters except alphanumeric and space
            .replace(/[^a-zA-Z0-9\s]/g, "")
            // Insere espaço entre letra e dígito para o separador aparecer no boundary
            // ("Artigo 2024" continua "artigo-2024" e "v2tool" vira "v-2-tool")
            .replace(/([a-zA-Z])(\d)/g, "$1 $2")
            .replace(/(\d)([a-zA-Z])/g, "$1 $2")
            // Trim spaces
            .trim();

        // Apply case format before replacing spaces
        switch (caseFormat) {
            case "lowercase":
                slug = slug.toLowerCase();
                break;
            case "uppercase":
                slug = slug.toUpperCase();
                break;
            case "words":
                slug = slug
                    .toLowerCase()
                    .split(" ")
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" ");
                break;
            // 'keep' - do nothing, maintain original case
        }

        // Replace spaces with chosen separator
        slug = slug.replace(/\s+/g, separator);

        return slug;
    }

    async copy(text) {
        if (!text) return;
        try {
            await copyText(text);
            showToast("Copiado!");
        } catch (error) {
            showToast("Não foi possível copiar", { variant: "error" });
            console.error("Erro ao copiar:", error);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    const tool = new SlugifyTool();
    tool.init();
});
