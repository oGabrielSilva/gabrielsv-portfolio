@php
    use App\Utils\SiteStats;
    $quickStats = SiteStats::quickStats();
    $topCategories = SiteStats::topCategories(5);
    $social = $site['social'] ?? [];
@endphp

<footer class="{{ $footerClass ?? '' }} border-t border-neutral-800 bg-neutral-950">
    {{-- Strip alive --}}
    <div class="border-b border-neutral-800/60 bg-neutral-900/40">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-x-6 gap-y-1 px-4 py-2.5 text-[11px] text-gray-500 sm:px-6">
            <span class="flex items-center gap-1.5">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-bulma-primary opacity-60"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-bulma-primary"></span>
                </span>
                Online
            </span>
            @if($quickStats['last_post_at'])
                <span aria-hidden="true" class="text-neutral-700">·</span>
                <span>
                    Último post
                    @if($quickStats['last_post_days_ago'] !== null && $quickStats['last_post_days_ago'] < 1)
                        hoje
                    @else
                        há {{ (int) $quickStats['last_post_days_ago'] }} {{ (int) $quickStats['last_post_days_ago'] === 1 ? 'dia' : 'dias' }}
                    @endif
                </span>
            @endif
            <span aria-hidden="true" class="text-neutral-700">·</span>
            <span>{{ $quickStats['posts_total'] }} {{ $quickStats['posts_total'] === 1 ? 'post' : 'posts' }}</span>
            <span aria-hidden="true" class="text-neutral-700">·</span>
            <span>{{ $quickStats['tools_total'] }} ferramentas</span>
        </div>
    </div>

    {{-- 4 colunas --}}
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
        <div class="grid grid-cols-2 gap-8 sm:grid-cols-4 sm:gap-10">
            {{-- Site --}}
            <div class="space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Site</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('index') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Início</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Blog</a></li>
                    <li><a href="{{ route('tools.index') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Ferramentas</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Sobre</a></li>
                    <li><a href="{{ route('now') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">/now</a></li>
                    <li><a href="{{ route('uses') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">/uses</a></li>
                </ul>
            </div>

            {{-- Conteúdo --}}
            <div class="space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Conteúdo</h4>
                <ul class="space-y-2 text-sm">
                    @forelse($topCategories as $cat)
                        <li>
                            <a href="{{ route('blog.category', $cat) }}" class="text-gray-400 transition-colors hover:text-bulma-primary">
                                {{ $cat->name }}
                                <span class="text-xs text-gray-600">({{ $cat->posts_count }})</span>
                            </a>
                        </li>
                    @empty
                        <li><span class="text-gray-600 text-xs">Sem categorias ainda</span></li>
                    @endforelse
                    <li>
                        <a href="/feed.xml" class="inline-flex items-center gap-1.5 text-gray-400 transition-colors hover:text-bulma-primary">
                            <i data-lucide="rss" class="size-3.5"></i>
                            RSS
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Conecte --}}
            <div class="space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Conecte</h4>
                <ul class="space-y-2 text-sm">
                    @if(!empty($social['github']))
                        <li>
                            <a href="{{ $social['github'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-gray-400 transition-colors hover:text-bulma-primary">
                                <i data-lucide="github" class="size-3.5"></i>
                                GitHub
                            </a>
                        </li>
                    @endif
                    @if(!empty($social['linkedin']))
                        <li>
                            <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-gray-400 transition-colors hover:text-bulma-link">
                                <i data-lucide="linkedin" class="size-3.5"></i>
                                LinkedIn
                            </a>
                        </li>
                    @endif
                    @if(!empty($social['email']))
                        <li>
                            <a href="{{ $social['email'] }}" class="inline-flex items-center gap-1.5 text-gray-400 transition-colors hover:text-bulma-primary">
                                <i data-lucide="mail" class="size-3.5"></i>
                                E-mail
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="/feed.json" class="inline-flex items-center gap-1.5 text-gray-400 transition-colors hover:text-bulma-primary">
                            <i data-lucide="code" class="size-3.5"></i>
                            JSON Feed
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Legal --}}
            <div class="space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('legal.show', 'privacidade') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Privacidade</a></li>
                    <li><a href="{{ route('legal.show', 'termos') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Termos</a></li>
                    <li><a href="{{ route('legal.show', 'cookies') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Cookies</a></li>
                    <li><a href="{{ route('stats') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Stats</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-neutral-800 pt-6 sm:flex-row">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ $site['author']['name'] ?? 'Gabriel' }} ·
                <span class="text-gray-600">Feito com Laravel + Tailwind</span>
            </p>
            <a
                href="#top"
                class="inline-flex items-center gap-1.5 rounded-full border border-neutral-800 bg-neutral-900 px-3 py-1.5 text-xs text-gray-400 transition-colors hover:border-bulma-primary/40 hover:text-bulma-primary"
                aria-label="Voltar ao topo"
            >
                <i data-lucide="arrow-up" class="size-3.5"></i>
                Topo
            </a>
        </div>
    </div>
</footer>
