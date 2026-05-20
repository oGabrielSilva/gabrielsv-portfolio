@php
    $turnstileKey = config('services.turnstile.site_key');
@endphp

<form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
    @csrf

    {{-- Honeypot: invisível pro usuário, irresistível pro bot --}}
    <div class="absolute -left-[9999px] h-0 w-0 overflow-hidden" aria-hidden="true">
        <label for="contact-website">Website (deixe em branco)</label>
        <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off" value="">
    </div>

    {{-- Mensagens de status --}}
    @if (session('contact_success'))
        <div role="status"
            class="rounded-xl border border-bulma-primary/40 bg-bulma-primary/10 px-4 py-3 text-sm text-bulma-primary">
            {{ session('contact_success') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert"
            class="rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <p class="font-semibold">Não consegui enviar:</p>
            <ul class="mt-1 list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="space-y-1.5">
            <label for="contact-name" class="block text-sm font-medium text-gray-300">Nome</label>
            <input type="text" id="contact-name" name="name" required minlength="2" maxlength="120"
                value="{{ old('name') }}"
                class="w-full rounded-lg border border-neutral-800 bg-neutral-900 px-3 py-2 text-sm text-white placeholder-gray-500 transition-colors focus:border-bulma-primary focus:outline-none">
        </div>

        <div class="space-y-1.5">
            <label for="contact-email" class="block text-sm font-medium text-gray-300">E-mail</label>
            <input type="email" id="contact-email" name="email" required maxlength="180"
                value="{{ old('email') }}"
                class="w-full rounded-lg border border-neutral-800 bg-neutral-900 px-3 py-2 text-sm text-white placeholder-gray-500 transition-colors focus:border-bulma-primary focus:outline-none">
        </div>
    </div>

    <div class="space-y-1.5">
        <label for="contact-subject" class="block text-sm font-medium text-gray-300">Assunto</label>
        <input type="text" id="contact-subject" name="subject" required minlength="3" maxlength="200"
            value="{{ old('subject') }}"
            class="w-full rounded-lg border border-neutral-800 bg-neutral-900 px-3 py-2 text-sm text-white placeholder-gray-500 transition-colors focus:border-bulma-primary focus:outline-none">
    </div>

    <div class="space-y-1.5">
        <label for="contact-message" class="block text-sm font-medium text-gray-300">Mensagem</label>
        <textarea id="contact-message" name="message" required minlength="10" maxlength="5000" rows="6"
            class="w-full resize-y rounded-lg border border-neutral-800 bg-neutral-900 px-3 py-2 text-sm text-white placeholder-gray-500 transition-colors focus:border-bulma-primary focus:outline-none">{{ old('message') }}</textarea>
    </div>

    @if ($turnstileKey)
        <div class="cf-turnstile" data-sitekey="{{ $turnstileKey }}" data-theme="dark"></div>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-bulma-primary px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-bulma-primary/90 focus:outline-none focus:ring-2 focus:ring-bulma-primary/50">
            <i data-lucide="send" class="size-4"></i>
            Enviar mensagem
        </button>
        <p class="text-xs text-gray-500">
            Respondo em até alguns dias úteis no e-mail informado.
        </p>
    </div>
</form>
