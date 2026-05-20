<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Lista de ferramentas disponíveis
     */
    public const TOOLS = [
        [
            'route' => 'tools.uuid',
            'routeMatch' => 'tools.uuid*',
            'slug' => 'uuid',
            'name' => 'UUID Generator',
            'description' => 'UUID v1, v4, v6, v7, CUID e NanoID. Sabe qual escolher? Cada um tem uma página explicando o uso.',
            'icon' => 'fingerprint',
            'color' => 'bulma-primary',
        ],
        [
            'route' => 'tools.lorem',
            'routeMatch' => 'tools.lorem',
            'slug' => 'lorem',
            'name' => 'Lorem Ipsum',
            'description' => 'Texto de preenchimento para mockup, wireframe e protótipo. Em palavras, parágrafos ou frases.',
            'icon' => 'align-left',
            'color' => 'blue-500',
        ],
        [
            'route' => 'tools.percentage',
            'routeMatch' => 'tools.percentage',
            'slug' => 'percentage',
            'name' => 'Calculadora de Porcentagem',
            'description' => 'Quanto é X% de Y, qual a variação entre dois valores e qual o valor original antes do desconto.',
            'icon' => 'percent',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.image-compressor',
            'routeMatch' => 'tools.image-compressor',
            'slug' => 'image-compressor',
            'name' => 'Compressor de Imagem',
            'description' => 'Várias imagens de uma vez, exporta JPEG, WebP e PNG no mesmo passo. Tudo no navegador, sem upload.',
            'icon' => 'image',
            'color' => 'orange-400',
        ],
        [
            'route' => 'tools.cpf',
            'routeMatch' => 'tools.cpf*',
            'slug' => 'cpf',
            'name' => 'CPF / CNPJ',
            'description' => 'CPF e CNPJ válidos para preencher formulário em ambiente de teste. Também valida números existentes.',
            'icon' => 'id-card',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.base64',
            'routeMatch' => 'tools.base64',
            'slug' => 'base64',
            'name' => 'Base64',
            'description' => 'Codifica e decodifica texto em Base64. Útil para inspecionar JWT, data URI e payload de API.',
            'icon' => 'lock',
            'color' => 'cyan-400',
        ],
        [
            'route' => 'tools.slugify',
            'routeMatch' => 'tools.slugify',
            'slug' => 'slugify',
            'name' => 'Slugify',
            'description' => 'Transforma título em slug para URL. Tira acento, espaço e símbolo, sem quebrar palavra em português.',
            'icon' => 'link',
            'color' => 'amber-400',
        ],
        [
            'route' => 'tools.json-formatter',
            'routeMatch' => 'tools.json-formatter',
            'slug' => 'json-formatter',
            'name' => 'JSON Formatter',
            'description' => 'Formata, minifica e valida JSON. Quando dá erro, mostra a linha e coluna exatas.',
            'icon' => 'braces',
            'color' => 'sky-400',
        ],
        [
            'route' => 'tools.cron',
            'routeMatch' => 'tools.cron',
            'slug' => 'cron',
            'name' => 'Cron Explainer',
            'description' => 'Cola uma expressão cron e a página explica em português, com as próximas datas de execução.',
            'icon' => 'clock',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.markdown',
            'routeMatch' => 'tools.markdown',
            'slug' => 'markdown',
            'name' => 'Markdown Preview',
            'description' => 'Escreve do lado esquerdo, vê o resultado renderizado do lado direito. Funciona para README, doc e post.',
            'icon' => 'file-text',
            'color' => 'blue-500',
        ],
        [
            'route' => 'tools.world-clock',
            'routeMatch' => 'tools.world-clock',
            'slug' => 'world-clock',
            'name' => 'Horário Mundial',
            'description' => 'Que horas são agora em São Paulo, Lisboa, Nova York, Tóquio. Para quem agenda call com time fora do Brasil.',
            'icon' => 'globe',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.keyboard-tester',
            'routeMatch' => 'tools.keyboard-tester',
            'slug' => 'keyboard-tester',
            'name' => 'Testador de Teclado',
            'description' => 'Pressiona uma tecla e ela acende na tela. Detecta tecla presa, falha intermitente e suporta ABNT2.',
            'icon' => 'keyboard',
            'color' => 'orange-400',
        ],
        [
            'route' => 'tools.password-generator',
            'routeMatch' => 'tools.password-generator',
            'slug' => 'password-generator',
            'name' => 'Gerador de Senhas',
            'description' => 'Senha forte gerada no seu navegador, nunca enviada para servidor. Você escolhe o tamanho e o que incluir.',
            'icon' => 'shield',
            'color' => 'red-400',
        ],
        [
            'route' => 'tools.color-picker',
            'routeMatch' => 'tools.color-picker',
            'slug' => 'color-picker',
            'name' => 'Seletor de Cores',
            'description' => 'Escolhe uma cor e recebe HEX, RGB, HSL e paletas complementares prontas para copiar.',
            'icon' => 'palette',
            'color' => 'pink-400',
        ],
        [
            'route' => 'tools.text-counter',
            'routeMatch' => 'tools.text-counter',
            'slug' => 'text-counter',
            'name' => 'Contador de Caracteres',
            'description' => 'Conta caracteres, palavras e tempo de leitura, com alerta de limite para title, meta description e tweet.',
            'icon' => 'type',
            'color' => 'sky-400',
        ],
        [
            'route' => 'tools.remove-duplicates',
            'routeMatch' => 'tools.remove-duplicates',
            'slug' => 'remove-duplicates',
            'name' => 'Remover Duplicadas',
            'description' => 'Cola uma lista (e-mails, leads, IDs) e a página devolve sem repetição. Pode ignorar case e ordenar.',
            'icon' => 'list-x',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.unit-converter',
            'routeMatch' => 'tools.unit-converter',
            'slug' => 'unit-converter',
            'name' => 'Conversor de Unidades',
            'description' => 'Quanto é 24px em rem? Digita o valor em uma unidade e as outras aparecem na hora. Root font-size editável.',
            'icon' => 'ruler',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.whatsapp-link',
            'routeMatch' => 'tools.whatsapp-link',
            'slug' => 'whatsapp-link',
            'name' => 'Gerador de Link WhatsApp',
            'description' => 'Cria link wa.me com número, DDI e mensagem pré-preenchida. Para botão de contato, bio e campanha.',
            'icon' => 'message-circle',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.email-link',
            'routeMatch' => 'tools.email-link',
            'slug' => 'email-link',
            'name' => 'Gerador de Link de E-mail',
            'description' => 'Monta link mailto: com CC, BCC, assunto e corpo preenchidos. Para botão de contato e assinatura.',
            'icon' => 'mail',
            'color' => 'sky-400',
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartilha a lista de ferramentas com todas as views
        View::share('toolsList', self::TOOLS);
        View::share('site', config('site'));

        // Define onde o AdSense pode aparecer: homepage, blog e tools.
        // Páginas estáticas (brand-guide, card-generator, /uses, /now, /sobre, /stats, /legal/*) ficam de fora.
        View::composer('partials.adsense', function ($view) {
            $route = Route::currentRouteName() ?? '';
            $showAds = $route === 'index'
                || Str::startsWith($route, 'blog.')
                || Str::startsWith($route, 'tools.');
            $view->with('showAds', $showAds);
        });

        // GA4: roda em todo o site público; bloqueia no /console (Filament admin).
        View::composer('partials.google-analytics', function ($view) {
            $route = Route::currentRouteName() ?? '';
            $showAnalytics = ! Str::startsWith($route, 'filament.');
            $view->with('showAnalytics', $showAnalytics);
        });
    }
}
