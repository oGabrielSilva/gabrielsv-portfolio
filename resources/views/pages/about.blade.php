@extends('layouts.blog')

@section('title', 'Sobre · '.config('site.author.name'))
@section('description', config('site.author.bio'))
@section('post_title', 'Sobre')

@push('jsonld')
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->forPerson(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode(app(\App\Services\JsonLdBuilder::class)->breadcrumbs([
        ['name' => 'Início', 'url' => url('/')],
        ['name' => 'Sobre', 'url' => route('about')],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <article class="space-y-10">
        <header class="space-y-4">
            <x-blog.breadcrumbs :items="[
                ['name' => 'Início', 'url' => url('/')],
                ['name' => 'Sobre'],
            ]" />
            <div class="flex items-center gap-5">
                <img
                    src="https://www.gravatar.com/avatar/{{ md5(strtolower(config('site.author.email'))) }}?d=mp&s=200"
                    alt="{{ config('site.author.name') }}"
                    class="size-20 rounded-full ring-2 ring-bulma-primary/40"
                    width="80"
                    height="80"
                >
                <div>
                    <h1 class="text-3xl font-bold text-white sm:text-4xl">{{ config('site.author.name') }}</h1>
                    <p class="text-sm text-bulma-primary">{{ config('site.author.role') }}</p>
                </div>
            </div>
            <p class="text-lg text-gray-300">{{ config('site.author.bio') }}</p>
        </header>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">O que faço</h2>
            <p class="text-gray-300 leading-relaxed">
                Desenvolvo aplicações web de ponta a ponta — back-end em <strong class="text-white">Laravel</strong>, front-end em <strong class="text-white">Blade + Tailwind + JS vanilla</strong>, infra em Docker. Foco em produto enxuto, performance e SEO técnico.
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Sobre este site</h2>
            <p class="text-gray-300 leading-relaxed">
                Este site é meu portfolio + blog + suíte de <a href="{{ route('tools.index') }}" class="text-bulma-primary hover:underline">{{ count(\App\Providers\AppServiceProvider::TOOLS) }} ferramentas online</a>. Tudo open-source, sem trackers, sem cookies de terceiros. Veja <a href="{{ route('legal.show', 'privacidade') }}" class="text-bulma-primary hover:underline">a política de privacidade</a> ou as <a href="{{ route('stats') }}" class="text-bulma-primary hover:underline">estatísticas em tempo real</a>.
            </p>
        </section>

        <section class="space-y-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-bulma-primary">Como me encontrar</h2>
            <ul class="space-y-2 text-gray-300">
                <li>
                    <a href="{{ config('site.social.github') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-bulma-primary hover:underline">
                        <x-icon-brand name="github" class="size-4" /> {{ config('site.social_handles.github') }}
                    </a>
                </li>
                <li>
                    <a href="{{ config('site.social.linkedin') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-bulma-link hover:underline">
                        <x-icon-brand name="linkedin" class="size-4" /> {{ config('site.social_handles.linkedin') }}
                    </a>
                </li>
                <li>
                    <a href="{{ config('site.social.email') }}" class="inline-flex items-center gap-2 text-gray-300 hover:text-bulma-primary">
                        <i data-lucide="mail" class="size-4"></i> {{ config('site.author.email') }}
                    </a>
                </li>
            </ul>
        </section>
    </article>
@endsection
