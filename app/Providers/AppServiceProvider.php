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
