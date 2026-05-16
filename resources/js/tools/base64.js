import { copyText } from '../utils/clipboard.js';
import { showToast } from '../utils/toast.js';

class Base64Tool {
    constructor() {
        this.currentTab = 'encode';
        this.elements = {};
    }

    init() {
        this.cacheElements();
        this.bindEvents();
    }

    cacheElements() {
        this.elements = {
            tabs: document.querySelectorAll('.tab-btn'),
            encodeSection: document.getElementById('encode-section'),
            decodeSection: document.getElementById('decode-section'),
            encodeInput: document.getElementById('encode-input'),
            encodeOutput: document.getElementById('encode-output'),
            encodeBtn: document.getElementById('encode-btn'),
            copyEncodeBtn: document.getElementById('copy-encode-btn'),
            decodeInput: document.getElementById('decode-input'),
            decodeOutput: document.getElementById('decode-output'),
            decodeBtn: document.getElementById('decode-btn'),
            copyDecodeBtn: document.getElementById('copy-decode-btn'),
            decodeError: document.getElementById('decode-error'),
            decodeErrorMessage: document.getElementById('decode-error-message'),
        };
    }

    bindEvents() {
        this.elements.tabs.forEach((tab) => {
            tab.addEventListener('click', () => this.switchTab(tab.dataset.tab));
        });

        this.elements.encodeBtn.addEventListener('click', () => this.encode());
        this.elements.encodeInput.addEventListener('input', () => this.encode());
        this.elements.copyEncodeBtn.addEventListener('click', () =>
            this.copy(this.elements.encodeOutput.value),
        );

        this.elements.decodeBtn.addEventListener('click', () => this.decode());
        this.elements.decodeInput.addEventListener('input', () => this.decode());
        this.elements.copyDecodeBtn.addEventListener('click', () =>
            this.copy(this.elements.decodeOutput.value),
        );
    }

    switchTab(tab) {
        if (tab === this.currentTab) return;
        this.currentTab = tab;

        this.elements.tabs.forEach((t) => {
            const isActive = t.dataset.tab === tab;
            t.classList.toggle('border-bulma-primary', isActive);
            t.classList.toggle('text-bulma-primary', isActive);
            t.classList.toggle('border-transparent', !isActive);
            t.classList.toggle('text-gray-400', !isActive);
        });

        this.elements.encodeSection.classList.toggle('hidden', tab !== 'encode');
        this.elements.decodeSection.classList.toggle('hidden', tab !== 'decode');
    }

    encode() {
        const input = this.elements.encodeInput.value;
        if (!input) {
            this.elements.encodeOutput.value = '';
            return;
        }

        try {
            const encoded = btoa(
                encodeURIComponent(input).replace(
                    /%([0-9A-F]{2})/g,
                    (_, p1) => String.fromCharCode(parseInt(p1, 16)),
                ),
            );
            this.elements.encodeOutput.value = encoded;
        } catch (error) {
            console.error('Erro ao codificar:', error);
            this.elements.encodeOutput.value = '';
        }
    }

    decode() {
        const input = this.elements.decodeInput.value.trim();
        if (!input) {
            this.elements.decodeOutput.value = '';
            this.elements.decodeError.classList.add('hidden');
            return;
        }

        try {
            const decoded = decodeURIComponent(
                Array.from(atob(input))
                    .map((c) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
                    .join(''),
            );
            this.elements.decodeOutput.value = decoded;
            this.elements.decodeError.classList.add('hidden');
        } catch (_error) {
            this.elements.decodeOutput.value = '';
            this.elements.decodeError.classList.remove('hidden');
            this.elements.decodeErrorMessage.textContent =
                'Texto Base64 inválido. Verifique se o texto está correto.';
        }
    }

    async copy(text) {
        if (!text) return;
        try {
            await copyText(text);
            showToast('Copiado!');
        } catch (error) {
            showToast('Não foi possível copiar', { variant: 'error' });
            console.error('Erro ao copiar:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Base64Tool().init();
});
