<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('_gabrielsv_blog_posts', 60 * 60 * 12, function () {
            try {
                $response = Http::timeout(5)->get('https://eu.gabrielsv.com/wp-json/wp/v2/posts?per_page=6&_embed');
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });

        $services = [
            [
                'icon' => 'fa-layer-group',
                'title' => 'Front-end',
                'description' => 'Desenvolvimento de interfaces reativas utilizando os frameworks modernos. Foco total em usabilidade e responsividade.',
                'color' => 'bulma-primary',
                'delay' => 0,
            ],
            [
                'icon' => 'fa-server',
                'title' => 'Backend & API',
                'description' => 'Arquitetura sólida com Laravel, Node.js ou a plataforma de sua escolha. Criação de APIs RESTful seguras, integrações complexas e modelagem de bancos de dados.',
                'color' => 'blue-500',
                'delay' => 100,
            ],
            [
                'icon' => 'fa-brands fa-wordpress',
                'title' => 'Soluções WordPress',
                'description' => 'Desenvolvimento de temas personalizados e plugins. Transformo o CMS mais usado do mundo em plataformas robustas e performáticas.',
                'color' => 'sky-400',
                'delay' => 200,
            ],
            [
                'icon' => 'fa-regular fa-envelope-open',
                'title' => 'Email Development',
                'description' => 'Desenvolvimento de e-mails compatíveis com os principais clientes de e-mail.',
                'color' => 'orange-400',
                'delay' => 300,
            ],
            [
                'icon' => 'fa-solid fa-gauge-high',
                'title' => 'Performance & SEO',
                'description' => 'Melhore a pontuação do seu sistema no Google. Otimização técnica, melhoria de tempo de carregamento e boas práticas de SEO aplicadas diretamente no código.',
                'color' => 'emerald-400',
                'delay' => 500,
            ]
        ];

        return view('index', [
            'posts' => $posts,
            'services' => $services
        ]);
    }
}
