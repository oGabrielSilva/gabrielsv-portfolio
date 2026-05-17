<footer class="{{ $footerClass ?? '' }} border-t border-neutral-800 py-8 bg-neutral-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
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
        <nav class="flex flex-wrap justify-center sm:justify-start gap-x-4 gap-y-1 text-xs text-gray-600">
            <a href="{{ route('legal.show', 'privacidade') }}" class="hover:text-bulma-primary transition-colors">Privacidade</a>
            <span aria-hidden="true">·</span>
            <a href="{{ route('legal.show', 'termos') }}" class="hover:text-bulma-primary transition-colors">Termos</a>
            <span aria-hidden="true">·</span>
            <a href="{{ route('legal.show', 'cookies') }}" class="hover:text-bulma-primary transition-colors">Cookies</a>
        </nav>
    </div>
</footer>
