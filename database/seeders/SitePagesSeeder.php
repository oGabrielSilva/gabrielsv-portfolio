<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class SitePagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'contato',
                'title' => 'Contato',
                'subtitle' => 'Envie uma mensagem ou me encontre nas redes.',
                'meta_description' => 'Entre em contato com Gabriel Henrique da Silva por formulário, e-mail ou redes sociais.',
                'address' => null,
                'body_html' => '<p>Tem um projeto, dúvida ou só quer trocar uma ideia? Manda mensagem abaixo ou use os canais sociais.</p>',
            ],
            [
                'slug' => 'sobre',
                'title' => 'Sobre',
                'subtitle' => 'Dev fullstack focado em Laravel, performance e SEO. Escrevo sobre o que aprendo.',
                'meta_description' => 'Sobre Gabriel Henrique da Silva. Dev fullstack focado em Laravel, performance e SEO.',
                'body_html' => <<<'HTML'
<h2>O que faço</h2>
<p>Desenvolvo aplicações web de ponta a ponta. Back-end em <strong>Laravel</strong>, front-end em <strong>Blade + Tailwind + JS vanilla</strong>, infra em Docker. Foco em produto enxuto, performance e SEO técnico.</p>

<h2>Sobre este site</h2>
<p>Este site é meu portfolio, blog e suíte de ferramentas online. Tudo open-source, sem trackers, sem cookies de terceiros. Você pode ver <a href="/legal/privacidade">a política de privacidade</a> ou <a href="/stats">as estatísticas em tempo real</a>.</p>

<h2>Como me encontrar</h2>
<p>Os links das minhas redes ficam logo abaixo desta página. Clique em qualquer ícone para abrir.</p>
HTML,
            ],
            [
                'slug' => 'uses',
                'title' => 'Setup',
                'subtitle' => 'Editor, terminal, hardware e libs que uso para escrever código todo dia. Atualizado quando troco alguma peça.',
                'meta_description' => 'Editor, terminal, hardware, fontes e libs que uso para programar todo dia.',
                'body_html' => <<<'HTML'
<h2>Editor & terminal</h2>
<ul>
    <li><strong>VS Code</strong> com extensions essenciais: PHP Intelephense, Laravel Blade, Tailwind IntelliSense, GitLens, Error Lens, ESLint.</li>
    <li><strong>Tema:</strong> One Dark Pro / Catppuccin Mocha. Fonte: <a href="https://github.com/tonsky/FiraCode" rel="noopener" target="_blank">Fira Code</a> com ligatures.</li>
    <li><strong>Terminal:</strong> Windows Terminal + PowerShell 7. Bash via WSL2 (Ubuntu) para tudo Docker.</li>
    <li><strong>Atalhos VSCode preferidos:</strong> Ctrl+P, Ctrl+Shift+P, Ctrl+. (quickfix), Alt+Click (multi-cursor).</li>
</ul>

<h2>Stack que respeito</h2>
<ul>
    <li><strong>Laravel</strong>, minha casa. PHP 8.3+, Eloquent, Blade, Filament para admin.</li>
    <li><strong>Tailwind CSS v4</strong> com design tokens via <code>@theme</code>, zero JS config.</li>
    <li><strong>Vanilla JS</strong> para componentes simples; <strong>Alpine.js</strong> quando precisa de reatividade leve.</li>
    <li><strong>MySQL</strong> em produção, <strong>SQLite</strong> em dev local.</li>
    <li><strong>Redis</strong> para cache e sessões.</li>
</ul>

<h2>Hardware</h2>
<ul>
    <li><strong>Notebook principal:</strong> que rode Docker sem chorar.</li>
    <li><strong>Teclado:</strong> mecânico, switches lineares.</li>
    <li><strong>Mouse:</strong> Logitech (qualquer um silencioso).</li>
</ul>

<h2>Browser & dev tools</h2>
<ul>
    <li><strong>Brave</strong> para navegar; <strong>Chrome</strong> para dev (Lighthouse, DevTools).</li>
    <li><strong>Bruno</strong> ou Postman para APIs.</li>
    <li><strong>phpMyAdmin</strong> + <strong>TablePlus</strong> para banco.</li>
</ul>

<p><em>Inspirado pela <a href="https://uses.tech" rel="noopener" target="_blank">uses.tech</a> do Wes Bos.</em></p>
HTML,
            ],
            [
                'slug' => 'now',
                'title' => 'Agora',
                'subtitle' => 'Inspirado pelo /now movement do Derek Sivers. O que estou fazendo neste momento, atualizado quando muda algo importante.',
                'meta_description' => 'O que estou fazendo, lendo, construindo e aprendendo neste momento.',
                'body_html' => <<<'HTML'
<h2>Construindo</h2>
<ul>
    <li>Migrando o blog do WordPress para Laravel + Filament. Série de posts, busca, OG dinâmico, dashboard de stats.</li>
    <li>Expandindo a suíte de <a href="/tools">ferramentas online</a> para devs brasileiros.</li>
</ul>

<h2>Aprendendo</h2>
<ul>
    <li>Filament 5 em profundidade. Workflows de admin, custom actions, widgets.</li>
    <li>SEO técnico aplicado a sites pequenos: schema.org, OG dinâmico, sitemaps segmentados.</li>
</ul>

<h2>Lendo / consumindo</h2>
<ul>
    <li>Newsletters de devs brasileiros e internacionais (Laravel News, Front-end Focus).</li>
    <li>Estudos de craft em UI: Rauno Freiberg, Josh Comeau, Linear blog.</li>
</ul>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            SitePage::updateOrCreate(['slug' => $page['slug']], $page);
        }

        $this->command?->info('SitePages: '.count($pages).' páginas garantidas.');
    }
}
