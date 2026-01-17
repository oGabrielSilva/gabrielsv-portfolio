import 'preline/preline';
import imageCompression from 'browser-image-compression';
import JSZip from 'jszip';

class ImageCompressor {
    constructor() {
        this.images = []; // { id, file, originalBlob, status, results: { format: { blob, size, url } } }
        this.nextId = 1;
        this.quality = 80;
        this.maxWidth = null;
        this.selectedFormats = new Set(['jpeg', 'webp']);

        // Elements
        this.uploadArea = document.getElementById('upload-area');
        this.fileInput = document.getElementById('file-input');
        this.settingsContainer = document.getElementById('settings-container');
        this.imagesContainer = document.getElementById('images-container');
        this.imagesList = document.getElementById('images-list');
        this.imageCount = document.getElementById('image-count');
        this.features = document.getElementById('features');

        // Settings elements
        this.qualityInput = document.getElementById('quality');
        this.qualityValue = document.getElementById('quality-value');
        this.widthButtons = document.querySelectorAll('[data-width-option]');
        this.widthLabel = document.getElementById('width-label');
        this.formatCheckboxes = {
            jpeg: document.getElementById('format-jpeg'),
            webp: document.getElementById('format-webp'),
            png: document.getElementById('format-png'),
        };

        // Action buttons
        this.compressAllBtn = document.getElementById('compress-all-btn');
        this.downloadAllBtn = document.getElementById('download-all-btn');
        this.clearAllBtn = document.getElementById('clear-all-btn');

        // Modal
        this.previewModal = document.getElementById('preview-modal');
        this.modalTitle = document.getElementById('modal-title');
        this.modalImage = document.getElementById('modal-image');
        this.closeModalBtn = document.getElementById('close-modal-btn');

        this.init();
    }

    init() {
        // File input
        this.fileInput?.addEventListener('change', (e) => this.handleFileInput(e));

        // Drag and drop
        this.uploadArea?.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.uploadArea.classList.add('border-bulma-primary', 'bg-bulma-primary/5');
        });

        this.uploadArea?.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.uploadArea.classList.remove('border-bulma-primary', 'bg-bulma-primary/5');
        });

        this.uploadArea?.addEventListener('drop', (e) => this.handleDrop(e));

        // Quality slider
        this.qualityInput?.addEventListener('input', () => {
            this.quality = parseInt(this.qualityInput.value);
            this.qualityValue.textContent = this.quality + '%';
        });

        // Width options
        this.widthButtons?.forEach(btn => {
            btn.addEventListener('click', () => {
                const value = btn.dataset.widthOption;
                this.maxWidth = value === 'null' ? null : parseInt(value);
                this.updateWidthButtons();

                const dropdown = document.querySelector('#width-dropdown');
                if (dropdown && window.HSDropdown) {
                    window.HSDropdown.close(dropdown);
                }
            });
        });

        // Format checkboxes
        Object.entries(this.formatCheckboxes).forEach(([format, checkbox]) => {
            checkbox?.addEventListener('change', () => {
                if (checkbox.checked) {
                    this.selectedFormats.add(format);
                } else {
                    this.selectedFormats.delete(format);
                }
            });
        });

        // Action buttons
        this.compressAllBtn?.addEventListener('click', () => this.compressAll());
        this.downloadAllBtn?.addEventListener('click', () => this.downloadAll());
        this.clearAllBtn?.addEventListener('click', () => this.clearAll());

        // Modal
        this.closeModalBtn?.addEventListener('click', () => this.closeModal());
        this.previewModal?.addEventListener('click', (e) => {
            if (e.target === this.previewModal) this.closeModal();
        });

        // Initialize Preline
        window.HSStaticMethods?.autoInit();

        // Update buttons initial state
        this.updateWidthButtons();
    }

    updateWidthButtons() {
        this.widthButtons?.forEach(btn => {
            const value = btn.dataset.widthOption;
            const btnValue = value === 'null' ? null : parseInt(value);

            if (btnValue === this.maxWidth) {
                btn.classList.add('bg-bulma-primary/10', 'text-bulma-primary');
                btn.classList.remove('text-gray-300');
            } else {
                btn.classList.remove('bg-bulma-primary/10', 'text-bulma-primary');
                btn.classList.add('text-gray-300');
            }
        });

        if (this.widthLabel) {
            this.widthLabel.textContent = this.maxWidth ? this.maxWidth + 'px' : 'Original';
        }
    }

    handleFileInput(event) {
        const files = Array.from(event.target.files);
        if (files.length > 0) this.addImages(files);
        event.target.value = ''; // Reset input
    }

    handleDrop(event) {
        event.preventDefault();
        this.uploadArea.classList.remove('border-bulma-primary', 'bg-bulma-primary/5');

        const files = Array.from(event.dataTransfer.files).filter(file =>
            file.type.startsWith('image/')
        );
        if (files.length > 0) this.addImages(files);
    }

    async addImages(files) {
        for (const file of files) {
            if (file.size > 20 * 1024 * 1024) {
                console.warn(`File ${file.name} is too large (max 20MB)`);
                continue;
            }

            const id = this.nextId++;
            const image = {
                id,
                file,
                originalBlob: file,
                status: 'ready', // ready, compressing, compressed, error
                results: {}
            };

            this.images.push(image);
            this.renderImageCard(image);
        }

        this.updateUI();
    }

    updateUI() {
        const hasImages = this.images.length > 0;

        this.settingsContainer?.classList.toggle('hidden', !hasImages);
        this.imagesContainer?.classList.toggle('hidden', !hasImages);
        this.features?.classList.toggle('hidden', hasImages);

        if (this.imageCount) {
            this.imageCount.textContent = this.images.length;
        }

        // Enable/disable download all button
        const hasCompressed = this.images.some(img => img.status === 'compressed');
        this.downloadAllBtn.disabled = !hasCompressed;
    }

    renderImageCard(image) {
        const card = document.createElement('div');
        card.id = `image-${image.id}`;
        card.className = 'bg-neutral-800/50 border border-neutral-700/50 rounded-xl p-4';

        const url = URL.createObjectURL(image.file);

        card.innerHTML = `
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <img src="${url}" alt="${image.file.name}"
                        class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                        data-image-id="${image.id}">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-2">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-white font-medium truncate">${image.file.name}</h3>
                            <p class="text-sm text-gray-500">
                                Original: ${this.formatSize(image.file.size)}
                            </p>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <button type="button" class="compress-single-btn py-2 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg bg-bulma-primary text-neutral-900 hover:bg-bulma-primary/90 transition-all"
                                data-image-id="${image.id}">
                                <i class="fa-solid fa-compress"></i>
                                Comprimir
                            </button>
                            <button type="button" class="remove-image-btn py-2 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-neutral-600 text-gray-400 hover:text-white hover:bg-neutral-700 transition-all"
                                data-image-id="${image.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="status-container">
                        <span class="inline-flex items-center gap-1 text-sm text-gray-400">
                            <i class="fa-solid fa-circle-dot"></i>
                            Pronto
                        </span>
                    </div>

                    <div class="results-container hidden mt-3 space-y-2">
                        <!-- Results will be added here -->
                    </div>
                </div>
            </div>
        `;

        this.imagesList.appendChild(card);

        // Add event listeners
        card.querySelector('.compress-single-btn')?.addEventListener('click', () =>
            this.compressImage(image.id)
        );
        card.querySelector('.remove-image-btn')?.addEventListener('click', () =>
            this.removeImage(image.id)
        );
        card.querySelector('img')?.addEventListener('click', () =>
            this.showPreview(image.id, 'original')
        );
    }

    async compressImage(imageId) {
        const image = this.images.find(img => img.id === imageId);
        if (!image || image.status === 'compressing') return;

        if (this.selectedFormats.size === 0) {
            alert('Selecione pelo menos um formato de saída');
            return;
        }

        image.status = 'compressing';
        this.updateImageCard(image);

        try {
            const formats = Array.from(this.selectedFormats);

            for (const format of formats) {
                const blob = await this.compressToFormat(image.file, format);
                const url = URL.createObjectURL(blob);

                image.results[format] = {
                    blob,
                    size: blob.size,
                    url
                };
            }

            image.status = 'compressed';
        } catch (error) {
            console.error('Compression error:', error);
            image.status = 'error';
        }

        this.updateImageCard(image);
        this.updateUI();
    }

    async compressToFormat(file, format) {
        const options = {
            maxWidthOrHeight: this.maxWidth || 4096,
            useWebWorker: true,
            fileType: `image/${format}`,
        };

        // Only add quality for JPEG and WebP
        if (format !== 'png') {
            options.initialQuality = this.quality / 100;
        }

        return await imageCompression(file, options);
    }

    async compressAll() {
        const uncompressed = this.images.filter(img => img.status === 'ready');

        for (const image of uncompressed) {
            await this.compressImage(image.id);
        }
    }

    updateImageCard(image) {
        const card = document.getElementById(`image-${image.id}`);
        if (!card) return;

        const statusContainer = card.querySelector('.status-container');
        const resultsContainer = card.querySelector('.results-container');
        const compressBtn = card.querySelector('.compress-single-btn');

        // Update status
        if (image.status === 'compressing') {
            statusContainer.innerHTML = `
                <span class="inline-flex items-center gap-2 text-sm text-bulma-primary">
                    <svg class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Comprimindo...
                </span>
            `;
            compressBtn.disabled = true;
        } else if (image.status === 'compressed') {
            statusContainer.innerHTML = `
                <span class="inline-flex items-center gap-1 text-sm text-emerald-400">
                    <i class="fa-solid fa-circle-check"></i>
                    Comprimido
                </span>
            `;
            compressBtn.disabled = true;

            // Show results
            resultsContainer.classList.remove('hidden');
            resultsContainer.innerHTML = Object.entries(image.results).map(([format, result]) => {
                const reduction = Math.round((1 - result.size / image.file.size) * 100);
                const color = reduction > 0 ? 'text-emerald-400' : 'text-orange-400';

                return `
                    <div class="flex items-center justify-between py-2 px-3 bg-neutral-900 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-gray-400 uppercase w-12">${format}</span>
                            <span class="text-sm text-white">${this.formatSize(result.size)}</span>
                            <span class="text-sm ${color}">${reduction > 0 ? '-' : '+'}${Math.abs(reduction)}%</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" class="preview-result-btn py-1 px-2 text-xs rounded-lg border border-neutral-600 text-gray-400 hover:text-white hover:bg-neutral-700 transition-all"
                                data-image-id="${image.id}" data-format="${format}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="download-result-btn py-1 px-2 text-xs rounded-lg border border-neutral-600 text-gray-400 hover:text-white hover:bg-neutral-700 transition-all"
                                data-image-id="${image.id}" data-format="${format}">
                                <i class="fa-solid fa-download"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            // Add event listeners to result buttons
            resultsContainer.querySelectorAll('.preview-result-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const imgId = parseInt(e.currentTarget.dataset.imageId);
                    const format = e.currentTarget.dataset.format;
                    this.showPreview(imgId, format);
                });
            });

            resultsContainer.querySelectorAll('.download-result-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const imgId = parseInt(e.currentTarget.dataset.imageId);
                    const format = e.currentTarget.dataset.format;
                    this.downloadSingle(imgId, format);
                });
            });
        } else if (image.status === 'error') {
            statusContainer.innerHTML = `
                <span class="inline-flex items-center gap-1 text-sm text-red-400">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Erro ao comprimir
                </span>
            `;
            compressBtn.disabled = false;
        }
    }

    removeImage(imageId) {
        const index = this.images.findIndex(img => img.id === imageId);
        if (index === -1) return;

        const image = this.images[index];

        // Revoke object URLs
        if (image.results) {
            Object.values(image.results).forEach(result => {
                if (result.url) URL.revokeObjectURL(result.url);
            });
        }

        // Remove from array
        this.images.splice(index, 1);

        // Remove from DOM
        const card = document.getElementById(`image-${imageId}`);
        card?.remove();

        this.updateUI();
    }

    clearAll() {
        // Revoke all object URLs
        this.images.forEach(image => {
            if (image.results) {
                Object.values(image.results).forEach(result => {
                    if (result.url) URL.revokeObjectURL(result.url);
                });
            }
        });

        this.images = [];
        this.imagesList.innerHTML = '';
        this.updateUI();
    }

    downloadSingle(imageId, format) {
        const image = this.images.find(img => img.id === imageId);
        if (!image || !image.results[format]) return;

        const result = image.results[format];
        const filename = this.getFilename(image.file.name, format);

        this.downloadBlob(result.blob, filename);
    }

    async downloadAll() {
        const compressed = this.images.filter(img => img.status === 'compressed');
        if (compressed.length === 0) return;

        const zip = new JSZip();

        compressed.forEach(image => {
            Object.entries(image.results).forEach(([format, result]) => {
                const filename = this.getFilename(image.file.name, format);
                zip.file(filename, result.blob);
            });
        });

        const blob = await zip.generateAsync({ type: 'blob' });
        this.downloadBlob(blob, `compressed-images-${Date.now()}.zip`);
    }

    showPreview(imageId, format) {
        const image = this.images.find(img => img.id === imageId);
        if (!image) return;

        let url;
        let title;

        if (format === 'original') {
            url = URL.createObjectURL(image.originalBlob);
            title = `${image.file.name} (Original)`;
        } else {
            const result = image.results[format];
            if (!result) return;
            url = result.url;
            title = `${this.getFilename(image.file.name, format)}`;
        }

        this.modalImage.src = url;
        this.modalTitle.textContent = title;
        this.previewModal.classList.remove('hidden');
    }

    closeModal() {
        this.previewModal.classList.add('hidden');
        this.modalImage.src = '';
    }

    downloadBlob(blob, filename) {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    getFilename(originalName, format) {
        const nameWithoutExt = originalName.replace(/\.[^/.]+$/, '');
        return `${nameWithoutExt}.${format}`;
    }

    formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ImageCompressor();
});
