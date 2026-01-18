<footer class="{{ $footerClass ?? '' }} border-t border-neutral-800 py-8 bg-neutral-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="text-gray-500 text-sm">
            &copy; {{ date('Y') }} Gabriel Henrique da Silva
        </div>
        <div class="flex items-center gap-6">
            <a href="https://github.com/oGabrielSilva" target="_blank"
                class="text-gray-500 hover:text-bulma-primary transition-colors text-xl">
                <i class="fa-brands fa-github"></i>
            </a>
            <a href="https://www.linkedin.com/in/ogabriel-henrique" target="_blank"
                class="text-gray-500 hover:text-bulma-link transition-colors text-xl">
                <i class="fa-brands fa-linkedin"></i>
            </a>
        </div>
    </div>
</footer>
