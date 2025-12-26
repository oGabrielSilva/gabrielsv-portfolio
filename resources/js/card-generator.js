// Card Generator Logic
// dom-to-image is loaded via CDN and available globally as 'domtoimage'

let CLASSES = {
    WARRIOR: {
        name: "Guerreiro",
        color: "#C41E3A",
        theme: "from-red-950/40 to-black",
        icon: "sword",
    },
    ARCANE: {
        name: "Arcano",
        color: "#1E90FF",
        theme: "from-blue-950/40 to-black",
        icon: "wand",
    },
    SHADOW: {
        name: "Sombra",
        color: "#4B0082",
        theme: "from-purple-950/40 to-black",
        icon: "skull",
    },
    SACRED: {
        name: "Sagrado",
        color: "#FFD700",
        theme: "from-yellow-950/40 to-black",
        icon: "sun",
    },
    BEAST: {
        name: "Besta",
        color: "#228B22",
        theme: "from-green-950/40 to-black",
        icon: "flame",
    },
    MECHANICAL: {
        name: "Mecânico",
        color: "#708090",
        theme: "from-slate-950/40 to-black",
        icon: "hammer",
    },
    ELEMENTAL: {
        name: "Elemental",
        color: "#FF4500",
        theme: "from-orange-950/40 to-black",
        icon: "wind",
    },
    ABYSSAL: {
        name: "Abissal",
        color: "#8A2BE2",
        theme: "from-indigo-950/60 to-black",
        icon: "infinity",
    },
};

const RARITIES = {
    COMMON: {
        name: "Comum",
        borderColor: "border-zinc-700",
        nameColor: "text-zinc-100",
        glow: "shadow-xl shadow-black",
        tag: "bg-zinc-800",
    },
    RARE: {
        name: "Rara",
        borderColor: "border-blue-500",
        nameColor: "text-blue-400",
        glow: "shadow-[0_0_15px_rgba(59,130,246,0.4)]",
        tag: "bg-blue-600",
    },
    HIDDEN: {
        name: "Oculta",
        borderColor: "border-purple-600",
        nameColor: "text-purple-400",
        glow: "shadow-[0_0_20px_rgba(147,51,234,0.5)]",
        tag: "bg-purple-600",
    },
    ETHEREAL: {
        name: "Etérea",
        borderColor: "border-amber-400",
        nameColor: "text-amber-300",
        glow: "shadow-[0_0_25px_rgba(251,191,36,0.6)]",
        tag: "bg-amber-500",
        special: "animate-pulse",
    },
};

// State
let isExporting = false;
let uploadedImage = null;

// Elements
let cardPreview,
    cardBackPreview,
    cardName,
    cardDescription,
    cardClass,
    cardRarity,
    cardType,
    summonType,
    cardAtk,
    cardDef,
    cardImage;
let previewName, previewClassRarity, previewDescription, previewAtk, previewDef;
let classIcon,
    artworkArea,
    typeBadge,
    overlayBadge,
    statsFooter,
    statsSection,
    artworkIcon;
let exportBtn;
let addClassBtn, classModal, closeModalBtn, cancelModalBtn, classForm;
let classKeyInput, classNameInput, classColorInput, classColorPicker, classThemeSelect, classIconSelect;

// Initialize
document.addEventListener("DOMContentLoaded", () => {
    // Load custom classes from localStorage
    loadCustomClasses();
    // Get form elements
    cardName = document.getElementById("card-name");
    cardDescription = document.getElementById("card-description");
    cardClass = document.getElementById("card-class");
    cardRarity = document.getElementById("card-rarity");
    cardType = document.getElementById("card-type");
    summonType = document.getElementById("summon-type");
    cardAtk = document.getElementById("card-atk");
    cardDef = document.getElementById("card-def");
    cardImage = document.getElementById("card-image");

    // Get preview elements
    cardPreview = document.getElementById("card-preview");
    cardBackPreview = document.getElementById("card-back-preview");
    previewName = document.getElementById("preview-name");
    previewClassRarity = document.getElementById("preview-class-rarity");
    previewDescription = document.getElementById("preview-description");
    previewAtk = document.getElementById("preview-atk");
    previewDef = document.getElementById("preview-def");
    classIcon = document.getElementById("class-icon");
    artworkArea = document.getElementById("artwork-area");
    typeBadge = document.getElementById("type-badge");
    overlayBadge = document.getElementById("overlay-badge");
    statsFooter = document.getElementById("stats-footer");
    statsSection = document.getElementById("stats-section");
    artworkIcon = document.getElementById("artwork-icon");
    exportBtn = document.getElementById("export-btn");

    // Add event listeners
    cardName.addEventListener("input", updatePreview);
    cardDescription.addEventListener("input", updatePreview);
    cardClass.addEventListener("change", updatePreview);
    cardRarity.addEventListener("change", updatePreview);
    cardType.addEventListener("change", updatePreview);
    summonType.addEventListener("change", updatePreview);
    cardAtk.addEventListener("input", updatePreview);
    cardDef.addEventListener("input", updatePreview);
    cardImage.addEventListener("change", handleImageUpload);
    exportBtn.addEventListener("click", handleExport);

    // Get modal elements
    addClassBtn = document.getElementById("add-class-btn");
    classModal = document.getElementById("class-modal");
    closeModalBtn = document.getElementById("close-modal-btn");
    cancelModalBtn = document.getElementById("cancel-modal-btn");
    classForm = document.getElementById("class-form");
    classKeyInput = document.getElementById("class-key");
    classNameInput = document.getElementById("class-name");
    classColorInput = document.getElementById("class-color");
    classColorPicker = document.getElementById("class-color-picker");
    classThemeSelect = document.getElementById("modal-class-theme");
    classIconSelect = document.getElementById("modal-class-icon");

    // Modal event listeners
    addClassBtn.addEventListener("click", openModal);
    closeModalBtn.addEventListener("click", closeModal);
    cancelModalBtn.addEventListener("click", closeModal);
    classModal.addEventListener("click", (e) => {
        if (e.target === classModal) closeModal();
    });
    classForm.addEventListener("submit", handleClassSubmit);

    // Sync color picker with text input
    classColorPicker.addEventListener("input", (e) => {
        classColorInput.value = e.target.value.toUpperCase();
    });
    classColorInput.addEventListener("input", (e) => {
        const value = e.target.value;
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            classColorPicker.value = value;
        }
    });

    // Auto-uppercase class key
    classKeyInput.addEventListener("input", (e) => {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z_]/g, "");
    });

    // Initial update
    updatePreview();
    updateClassSelect();
});

function updatePreview() {
    const classInfo = CLASSES[cardClass.value];
    const rarityInfo = RARITIES[cardRarity.value];
    const isEntity = cardType.value === "ENTITY";
    const isOverlay = summonType.value === "OVERLAY";

    // Update name with trim
    const nameValue = cardName.value.trim() || "Nome da Carta";
    previewName.textContent = nameValue;
    previewName.className = `font-black text-[13px] tracking-tight truncate uppercase italic leading-tight ${rarityInfo.nameColor}`;

    // Update class and rarity text
    previewClassRarity.textContent = `${classInfo.name} • ${rarityInfo.name}`;

    // Update description with trim
    const descValue = cardDescription.value.trim() || "Descrição do efeito...";
    previewDescription.textContent = descValue;

    // Update class icon - Substitui o container com novo elemento
    const classIconContainer = document.getElementById("class-icon-container");
    if (classIconContainer) {
        classIconContainer.innerHTML = `<i id="class-icon" data-lucide="${classInfo.icon}" class="w-4 h-4" style="color: ${classInfo.color};"></i>`;
        classIcon = classIconContainer.querySelector("#class-icon");
    }

    // Update artwork area gradient
    artworkArea.className = `relative flex-grow bg-gradient-to-br ${classInfo.theme} flex items-center justify-center overflow-hidden border-b border-white/5`;

    // Update artwork - use uploaded image or icon
    if (uploadedImage) {
        artworkIcon.innerHTML = `<img src="${uploadedImage}" alt="Card artwork" class="w-full h-full object-cover" style="max-width: 100%; max-height: 100%;" />`;
    } else {
        // Update artwork icon based on card type
        const iconType = isEntity ? "star" : "zap";
        artworkIcon.innerHTML = `<i data-lucide="${iconType}" class="w-17.5 h-17.5" style="color: rgba(255,255,255,0.03); stroke-width: 1;"></i>`;
    }

    // Update card border and glow
    cardPreview.className = `relative w-64 h-96 rounded-[1.25rem] border-[3px] ${rarityInfo.borderColor} ${rarityInfo.glow} overflow-hidden bg-[#050505] text-white flex flex-col transition-all duration-500`;

    // Add special animation for Ethereal
    if (rarityInfo.special) {
        artworkIcon.classList.add("animate-pulse");
    } else {
        artworkIcon.classList.remove("animate-pulse");
    }

    // Update type badge
    typeBadge.textContent = cardType.value;
    typeBadge.className = `text-[7px] px-1.5 py-0.5 rounded-sm font-black text-white uppercase tracking-tighter ${rarityInfo.tag}`;

    // Show/hide overlay badge
    if (isOverlay) {
        overlayBadge.classList.remove("hidden");
        overlayBadge.classList.add("inline-flex");
    } else {
        overlayBadge.classList.add("hidden");
        overlayBadge.classList.remove("inline-flex");
    }

    // Show/hide stats section in form
    if (isEntity) {
        statsSection.classList.remove("hidden");
        statsFooter.classList.remove("hidden");
        previewAtk.textContent = cardAtk.value.trim();
        previewDef.textContent = cardDef.value.trim();
    } else {
        statsSection.classList.add("hidden");
        statsFooter.classList.add("hidden");
    }

    // Recreate Lucide icons
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        uploadedImage = e.target.result;
        updatePreview();
    };
    reader.readAsDataURL(file);
}

async function handleExport() {
    if (isExporting) return;

    isExporting = true;
    updateButtonState(true);

    try {
        // Wait a bit to ensure DOM is stable
        await new Promise((resolve) => setTimeout(resolve, 100));

        // Export only the front card
        const dataUrl = await domtoimage.toPng(cardPreview, {
            quality: 1,
            width: cardPreview.offsetWidth * 3,
            height: cardPreview.offsetHeight * 3,
            style: {
                transform: "scale(3)",
                transformOrigin: "top left",
                width: cardPreview.offsetWidth + "px",
                height: cardPreview.offsetHeight + "px",
            },
        });

        // Download the image
        const link = document.createElement("a");
        const fileName = cardName.value.trim().replace(/\s+/g, "_") || "card";
        link.download = `${fileName}.png`;
        link.href = dataUrl;
        link.click();

    } catch (err) {
        console.error("Export error:", err);
        alert("Erro ao exportar. Tente novamente.");
    } finally {
        isExporting = false;
        updateButtonState(false);
    }
}

function updateButtonState(loading) {
    exportBtn.disabled = loading;
    if (loading) {
        exportBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Exportando...
        `;
    } else {
        exportBtn.innerHTML = `
            <i data-lucide="download" class="w-4 h-4"></i>
            Exportar Carta (Frente)
        `;
        if (typeof lucide !== "undefined") {
            lucide.createIcons();
        }
    }
}

// Modal Functions
function openModal() {
    classModal.classList.remove("hidden");
    classModal.classList.add("flex");
    classForm.reset();

    // Recreate icons after modal is shown
    setTimeout(() => {
        if (typeof lucide !== "undefined") {
            lucide.createIcons();
        }
    }, 50);
}

function closeModal() {
    classModal.classList.add("hidden");
    classModal.classList.remove("flex");
    classForm.reset();
}

function handleClassSubmit(e) {
    e.preventDefault();

    // Verify elements exist
    if (!classKeyInput || !classNameInput || !classColorInput || !classThemeSelect || !classIconSelect) {
        console.error("Form elements not found:", {
            classKeyInput,
            classNameInput,
            classColorInput,
            classThemeSelect,
            classIconSelect
        });
        alert("Erro ao acessar os campos do formulário.");
        return;
    }

    const key = classKeyInput.value.trim().toUpperCase();
    const name = classNameInput.value.trim();
    const color = classColorInput.value.trim();
    const theme = classThemeSelect.value.trim();
    const icon = classIconSelect.value.trim();

    // Validate
    if (!key || !name || !color || !theme || !icon) {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    if (!/^[A-Z_]+$/.test(key)) {
        alert("A chave deve conter apenas letras maiúsculas e underscores.");
        return;
    }

    if (!/^#[0-9A-Fa-f]{6}$/.test(color)) {
        alert("A cor deve estar no formato HEX (#RRGGBB).");
        return;
    }

    if (CLASSES[key]) {
        const confirm = window.confirm(`A classe "${key}" já existe. Deseja substituir?`);
        if (!confirm) return;
    }

    // Add new class
    CLASSES[key] = {
        name: name,
        color: color,
        theme: theme,
        icon: icon,
    };

    // Save to localStorage
    saveCustomClasses();

    // Update select
    updateClassSelect();

    // Select the new class
    cardClass.value = key;
    updatePreview();

    // Close modal
    closeModal();

    alert(`Classe "${name}" adicionada com sucesso!`);
}

function loadCustomClasses() {
    try {
        const saved = localStorage.getItem("customCardClasses");
        if (saved) {
            const customClasses = JSON.parse(saved);
            // Merge with default classes (custom classes can override defaults)
            CLASSES = { ...CLASSES, ...customClasses };
        }
    } catch (err) {
        console.error("Error loading custom classes:", err);
    }
}

function saveCustomClasses() {
    try {
        // Save only custom classes (exclude defaults)
        const defaultKeys = ["WARRIOR", "ARCANE", "SHADOW", "SACRED", "BEAST", "MECHANICAL", "ELEMENTAL", "ABYSSAL"];
        const customClasses = {};

        for (const key in CLASSES) {
            if (!defaultKeys.includes(key)) {
                customClasses[key] = CLASSES[key];
            }
        }

        localStorage.setItem("customCardClasses", JSON.stringify(customClasses));
    } catch (err) {
        console.error("Error saving custom classes:", err);
    }
}

function updateClassSelect() {
    const currentValue = cardClass.value;
    const select = cardClass;

    // Clear all options
    select.innerHTML = "";

    // Add all classes (sorted alphabetically)
    const sortedClasses = Object.keys(CLASSES).sort((a, b) => {
        return CLASSES[a].name.localeCompare(CLASSES[b].name);
    });

    sortedClasses.forEach((key) => {
        const option = document.createElement("option");
        option.value = key;
        option.textContent = CLASSES[key].name;
        select.appendChild(option);
    });

    // Restore previous value if it exists
    if (CLASSES[currentValue]) {
        select.value = currentValue;
    } else if (sortedClasses.length > 0) {
        select.value = sortedClasses[0];
    }
}
