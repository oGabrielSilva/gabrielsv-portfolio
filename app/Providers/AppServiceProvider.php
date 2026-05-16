<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            'description' => 'Gere UUIDs em diferentes versões (v1, v4, v6, v7), CUIDs e NanoIDs',
            'icon' => 'fingerprint',
            'color' => 'bulma-primary',
        ],
        [
            'route' => 'tools.lorem',
            'routeMatch' => 'tools.lorem',
            'slug' => 'lorem',
            'name' => 'Lorem Ipsum',
            'description' => 'Gere textos placeholder para seus projetos',
            'icon' => 'align-left',
            'color' => 'blue-500',
        ],
        [
            'route' => 'tools.percentage',
            'routeMatch' => 'tools.percentage',
            'slug' => 'percentage',
            'name' => 'Calculadora de Porcentagem',
            'description' => 'Calcule porcentagens, aumentos e descontos facilmente',
            'icon' => 'percent',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.image-compressor',
            'routeMatch' => 'tools.image-compressor',
            'slug' => 'image-compressor',
            'name' => 'Compressor de Imagem',
            'description' => 'Comprima imagens PNG e JPG mantendo a qualidade',
            'icon' => 'image',
            'color' => 'orange-400',
        ],
        [
            'route' => 'tools.cpf',
            'routeMatch' => 'tools.cpf*',
            'slug' => 'cpf',
            'name' => 'CPF / CNPJ',
            'description' => 'Gere e valide CPF e CNPJ para testes',
            'icon' => 'id-card',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.base64',
            'routeMatch' => 'tools.base64',
            'slug' => 'base64',
            'name' => 'Base64',
            'description' => 'Codifique e decodifique texto em Base64',
            'icon' => 'lock',
            'color' => 'cyan-400',
        ],
        [
            'route' => 'tools.slugify',
            'routeMatch' => 'tools.slugify',
            'slug' => 'slugify',
            'name' => 'Slugify',
            'description' => 'Converta texto para slug URL-friendly',
            'icon' => 'link',
            'color' => 'amber-400',
        ],
        [
            'route' => 'tools.json-formatter',
            'routeMatch' => 'tools.json-formatter',
            'slug' => 'json-formatter',
            'name' => 'JSON Formatter',
            'description' => 'Formate, minifique e valide JSON online',
            'icon' => 'braces',
            'color' => 'sky-400',
        ],
        [
            'route' => 'tools.cron',
            'routeMatch' => 'tools.cron',
            'slug' => 'cron',
            'name' => 'Cron Explainer',
            'description' => 'Entenda expressões cron e veja próximas execuções',
            'icon' => 'clock',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.markdown',
            'routeMatch' => 'tools.markdown',
            'slug' => 'markdown',
            'name' => 'Markdown Preview',
            'description' => 'Edite e visualize Markdown em tempo real',
            'icon' => 'file-text',
            'color' => 'blue-500',
        ],
        [
            'route' => 'tools.world-clock',
            'routeMatch' => 'tools.world-clock',
            'slug' => 'world-clock',
            'name' => 'Horário Mundial',
            'description' => 'Veja o horário atual em diferentes fusos do mundo',
            'icon' => 'globe',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.keyboard-tester',
            'routeMatch' => 'tools.keyboard-tester',
            'slug' => 'keyboard-tester',
            'name' => 'Testador de Teclado',
            'description' => 'Teste todas as teclas do seu teclado visualmente',
            'icon' => 'keyboard',
            'color' => 'orange-400',
        ],
        [
            'route' => 'tools.password-generator',
            'routeMatch' => 'tools.password-generator',
            'slug' => 'password-generator',
            'name' => 'Gerador de Senhas',
            'description' => 'Gere senhas fortes e seguras com opções customizáveis',
            'icon' => 'shield',
            'color' => 'red-400',
        ],
        [
            'route' => 'tools.color-picker',
            'routeMatch' => 'tools.color-picker',
            'slug' => 'color-picker',
            'name' => 'Seletor de Cores',
            'description' => 'Converta cores entre HEX, RGB e HSL e gere paletas',
            'icon' => 'palette',
            'color' => 'pink-400',
        ],
        [
            'route' => 'tools.text-counter',
            'routeMatch' => 'tools.text-counter',
            'slug' => 'text-counter',
            'name' => 'Contador de Caracteres',
            'description' => 'Conte caracteres, palavras, linhas e tempo de leitura',
            'icon' => 'type',
            'color' => 'sky-400',
        ],
        [
            'route' => 'tools.case-converter',
            'routeMatch' => 'tools.case-converter',
            'slug' => 'case-converter',
            'name' => 'Conversor de Case',
            'description' => 'Converta texto entre UPPER, lower, camelCase, snake_case e mais',
            'icon' => 'case-sensitive',
            'color' => 'amber-400',
        ],
        [
            'route' => 'tools.remove-duplicates',
            'routeMatch' => 'tools.remove-duplicates',
            'slug' => 'remove-duplicates',
            'name' => 'Remover Duplicadas',
            'description' => 'Remova linhas duplicadas de qualquer texto',
            'icon' => 'list-x',
            'color' => 'emerald-400',
        ],
        [
            'route' => 'tools.unit-converter',
            'routeMatch' => 'tools.unit-converter',
            'slug' => 'unit-converter',
            'name' => 'Conversor de Unidades',
            'description' => 'Converta entre px, rem, em, %, pt e cm em CSS',
            'icon' => 'ruler',
            'color' => 'violet-400',
        ],
        [
            'route' => 'tools.url-validator',
            'routeMatch' => 'tools.url-validator',
            'slug' => 'url-validator',
            'name' => 'Validador de URL',
            'description' => 'Valide e analise URLs em seus componentes',
            'icon' => 'link-2',
            'color' => 'cyan-400',
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
    }
}
