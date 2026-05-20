@php
    use App\Utils\SiteStats;
    $quickStats = SiteStats::quickStats();
    $topCategories = SiteStats::topCategories(5);
    $social = $site['social'] ?? [];

    // Mapa de rede -> [hover color class, label]. Ordem do array = ordem visual.
    // Só renderiza se o link existir no config/site.php (filter abaixo).
    $socialIcons = collect([
        'github' => ['hover' => 'hover:text-bulma-primary', 'label' => 'GitHub'],
        'linkedin' => ['hover' => 'hover:text-bulma-link', 'label' => 'LinkedIn'],
        'x' => ['hover' => 'hover:text-white', 'label' => 'X (Twitter)'],
        'email' => ['hover' => 'hover:text-bulma-primary', 'label' => 'E-mail'],
    ])->filter(fn ($_, $key) => ! empty($social[$key]));
@endphp

<footer class="{{ $footerClass ?? '' }} border-t border-neutral-800 bg-neutral-950">
    {{-- Strip alive --}}
    <div class="border-b border-neutral-800/60 bg-neutral-900/40">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-center gap-x-6 gap-y-1 px-4 py-2.5 text-[11px] text-gray-500 sm:px-6">
            <a href="{{ route('stats') }}" class="flex items-center gap-1.5 transition-colors hover:text-bulma-primary" title="Ver estatísticas do site">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-bulma-primary opacity-60"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-bulma-primary"></span>
                </span>
                Online
            </a>
            @if($quickStats['last_post_at'])
                <span aria-hidden="true" class="text-neutral-700">·</span>
                @if(!empty($quickStats['last_post_slug']))
                    <a href="{{ route('blog.show', $quickStats['last_post_slug']) }}" class="transition-colors hover:text-bulma-primary">
                        Último post
                        @if($quickStats['last_post_days_ago'] !== null && $quickStats['last_post_days_ago'] < 1)
                            hoje
                        @else
                            há {{ (int) $quickStats['last_post_days_ago'] }} {{ (int) $quickStats['last_post_days_ago'] === 1 ? 'dia' : 'dias' }}
                        @endif
                    </a>
                @else
                    <span>
                        Último post
                        @if($quickStats['last_post_days_ago'] !== null && $quickStats['last_post_days_ago'] < 1)
                            hoje
                        @else
                            há {{ (int) $quickStats['last_post_days_ago'] }} {{ (int) $quickStats['last_post_days_ago'] === 1 ? 'dia' : 'dias' }}
                        @endif
                    </span>
                @endif
            @endif
            <span aria-hidden="true" class="text-neutral-700">·</span>
            <a href="{{ route('blog.index') }}" class="transition-colors hover:text-bulma-primary">{{ $quickStats['posts_total'] }} {{ $quickStats['posts_total'] === 1 ? 'post' : 'posts' }}</a>
            <span aria-hidden="true" class="text-neutral-700">·</span>
            <a href="{{ route('tools.index') }}" class="transition-colors hover:text-bulma-primary">{{ $quickStats['tools_total'] }} ferramentas</a>
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
                    <li><a href="{{ route('contact') }}" class="text-gray-400 transition-colors hover:text-bulma-primary">Contato</a></li>
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

            {{-- Conecte (ícone + nome, dinâmico via config/site.php) --}}
            <div class="space-y-3">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Conecte</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($socialIcons as $key => $meta)
                        <li>
                            <a
                                href="{{ $social[$key] }}"
                                @if($key !== 'email') target="_blank" rel="noopener me" @endif
                                class="inline-flex items-center gap-1.5 text-gray-400 transition-colors {{ $meta['hover'] }}"
                                aria-label="{{ $meta['label'] }}"
                            >
                                <x-icon-brand :name="$key" class="size-4.5" />
                                {{ $meta['label'] }}
                            </a>
                        </li>
                    @endforeach
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
