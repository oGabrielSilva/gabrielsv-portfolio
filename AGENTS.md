# AGENTS.md — gabrielsv.com/portfolio

Instruções para agentes de IA que atuam neste repositório.

## Visão Geral do Projeto

Portfolio pessoal, blog e suíte de ferramentas online de **Gabriel Henrique da Silva**, desenvolvedor fullstack. O projeto combina uma landing page de portfolio, um blog completo com admin Filament, páginas mosaico editáveis (`/sobre`, `/uses`, `/now`, `/contato`) e uma suíte crescente de ferramentas utilitárias dev-first em pt-BR.

Domínio em produção: **eu.gabrielsv.com** (Hostinger compartilhada). Admin em `eu.gabrielsv.com/console` (Filament).

## Stack Técnico

| Camada       | Tecnologia                                                              |
| ------------ | ----------------------------------------------------------------------- |
| Backend      | **Laravel 12** (PHP 8.2+, prod em PHP 8.5)                              |
| Admin        | **Filament 5** (`/console`)                                             |
| Frontend     | **Blade** + **Tailwind CSS v4** + **Vanilla JS** (ES modules)           |
| Build        | **Vite 7** com `laravel-vite-plugin`                                    |
| UI Library   | **Preline UI** (dropdowns, accordion)                                   |
| Ícones       | **Lucide** (tree-shake explícito) / SVGs locais para marcas             |
| Animações    | **AOS** (Animate On Scroll) — só na homepage                            |
| Database     | MySQL 5.7 (Docker local) / MySQL (Hostinger prod)                       |
| Cache/Queue  | Redis (Docker) / file driver (prod)                                     |
| Mídia        | `spatie/laravel-medialibrary`                                           |
| Feeds        | `spatie/laravel-feed` (RSS, Atom, JSON Feed em `/feed.xml`, `/atom.xml`)|
| Markdown     | Tempest/highlight (syntax) + custom MarkupRenderer                      |
| Anti-bot     | Cloudflare Turnstile + honeypot + Laravel throttle                      |
| Analytics    | GA4 (`G-YYD5Q9MDYZ`) + AdSense Auto Ads (`ca-pub-…`)                    |
| Infra local  | Docker Compose (Nginx + PHP-FPM + MySQL + Redis + Mailpit)              |

## Estrutura de Diretórios

```
portfolio/
├── app/
│   ├── Filament/
│   │   ├── Resources/        # Posts, Categories, Tags, Users, SitePages,
│   │   │                     # LegalPages, ContactMessages
│   │   └── Widgets/          # StatsOverview, PageViewsChart, etc.
│   ├── Http/Controllers/     # Home, Blog, Tools, Legal, SitePage, Contact,
│   │                         # Sitemap, OgImage, Stats, WorldClock
│   ├── Models/               # Post, Category, Tag, User, SitePage,
│   │                         # LegalPage, PageView, ContactMessage
│   ├── Providers/            # AppServiceProvider (TOOLS array + View::composer)
│   ├── Services/             # UuidService, LoremService, WorldClockService,
│   │                         # MarkupRenderer, JsonLdBuilder, TableOfContents
│   ├── Utils/                # BlogHelper, SiteStats
│   └── View/Components/      # AboutSection, BlogPostCard, ServiceCard, IconBrand
├── resources/
│   ├── views/
│   │   ├── layouts/          # app (home), blog, tools
│   │   ├── partials/         # head, footer, command-palette,
│   │   │                     # google-analytics, adsense
│   │   ├── components/       # contact-form, icon-brand, etc.
│   │   ├── tools/            # Uma view por tool
│   │   ├── blog/             # index, show, search, category, tag, series
│   │   ├── pages/site-page.blade.php   # /sobre, /uses, /now, /contato
│   │   ├── legal/            # privacidade, termos, cookies
│   │   ├── index.blade.php
│   │   ├── brand-guide.blade.php
│   │   └── card-generator.blade.php
│   ├── icons/                # SVGs de marca (github, linkedin, x, email)
│   ├── js/
│   │   ├── app.js            # AOS, Preline, mobile menu, command palette
│   │   ├── lucide-icons.js   # Tree-shake explícito de Lucide
│   │   ├── bootstrap.js
│   │   ├── tools/            # Uma classe ES6 por tool (auto-injetada no Vite)
│   │   ├── utils/            # clipboard, toast, api, dom, lucide,
│   │   │                     # ics-builder, calendar-links
│   │   └── card-generator.js
│   └── css/
│       └── app.css           # Tailwind v4 + @theme + service card variants
├── routes/web.php            # Todas as rotas
├── config/                   # site.php (config central), services.php,
│                             # feed.php, media-library.php
├── database/
│   ├── migrations/           # Inclui posts, categories, tags, media,
│   │                         # legal_pages, site_pages, contact_messages,
│   │                         # page_views
│   └── seeders/              # AdminUser, LegalPages, SitePages
├── docker/                   # Dockerfile PHP + Nginx config
├── compose.yml               # Docker Compose (app, nginx, db, redis, mailpit)
├── BRAND_GUIDE.md            # Design system
├── TOOLS_ROADMAP.md          # Checklist de tools
└── plan-tools.md             # Planejamento detalhado
```

## Convenções do Projeto

### Backend (PHP/Laravel)

- **Controllers** são simples — delegam pra `Services/`.
- **Services** (`app/Services/`) encapsulam lógica de negócio reutilizável.
- **TOOLS** é uma constante em `AppServiceProvider` com metadata de cada tool (route, slug, name, icon, color, description). Compartilhada com todas as views via `View::share('toolsList', ...)`.
- **`$site`** vem de `config('site')` (também compartilhada via `View::share`).
- **View composers** (`AppServiceProvider::boot`) controlam onde AdSense e GA carregam: `showAds` só em home/blog/tools; `showAnalytics` em todo lugar exceto `/console`.
- **Filament admin** em `/console`. Recursos em `app/Filament/Resources/<Plural>/<Resource>.php` com pastas `Pages/`, `Tables/`, `Schemas/`.

### Frontend (JS)

- Cada tool tem uma **classe ES6** em `resources/js/tools/`.
- Tools 100% client-side: Percentage, Image Compressor, CPF/CNPJ, Base64, Slugify, JSON Formatter, Cron, Markdown, World Clock, Keyboard Tester, Password, Color Picker, WhatsApp Link, Email Link, ICS Generator, Text Counter, Remove Duplicates, Unit Converter.
- Tools com backend (AJAX POST): UUID Generator, Lorem Ipsum, World Clock (busca de cidade via Nominatim).
- **`resources/js/utils/`** contém helpers puros reutilizáveis: `clipboard.js` (`copyText`), `toast.js` (`showToast`), `api.js` (`postJson`, `ApiError`), `ics-builder.js`, `calendar-links.js`.
- Scripts de tools são injetados via `@push('scripts')` + `@vite(...)`.

### Lucide icons (tree-shake)

- `resources/js/lucide-icons.js` faz **tree-shake explícito**: só ícones listados aqui renderizam.
- Ao adicionar `<i data-lucide="novo-icone">` em qualquer view, importe em PascalCase no `lucide-icons.js` (ex: `calendar-plus` → `import { CalendarPlus }`).
- Ícone não listado **some sem erro** — auditar com:
  ```bash
  rg 'data-lucide="([a-z0-9-]+)"' -or '$1' --no-filename | sort -u
  ```
- Lucide v1 removeu vários ícones de marca (Github, LinkedIn, Twitter). Para marcas, use SVGs em `resources/icons/` consumidos via `<x-icon-brand name="github" />`.

### Estilos (CSS/Tailwind)

- Tailwind CSS **v4** com `@import "tailwindcss"` (sem `tailwind.config.js`).
- Design tokens via `@theme {}` no `app.css`.
- Cores custom: `bulma-primary` (#00d1b2), `bulma-link`, `bulma-dark`.
- Variantes `.service-card--{color}` definidas manualmente no `app.css`.
- Dark mode é o padrão (sem toggle de light mode).
- **`.flex { flex-wrap: wrap }` é global e intencional** — não remover.

### Layouts

- **`layouts/app.blade.php`**: homepage e algumas páginas (sobre, contato, uses, now via `pages/site-page.blade.php`).
- **`layouts/blog.blade.php`**: blog (index, show, busca, categoria, tag, série).
- **`layouts/tools.blade.php`**: ferramentas. Sidebar desktop (`lg:w-72`) com listagem; menu mobile separado.

### Vite

- `vite.config.js` injeta automaticamente todos os JS de `resources/js/tools/` via `getFiles()`.
- Entrypoints fixos: `app.css`, `app.js`, `card-generator.js`, `resources/css/filament-admin.css`.
- `public/build/` é versionado (committed) — produção lê o build do repo, sem `npm install` no servidor.

## Hostinger (produção)

### Estrutura no servidor

A Hostinger é shared hosting com LiteSpeed + PHP 8.5.4. Layout específico:

```
~/                                       # Home do usuário SSH
├── domains/
│   └── eu.gabrielsv.com/
│       ├── laravel/                     # ★ Raiz do Laravel (git clone aqui)
│       │   ├── app/  config/  database/  resources/
│       │   ├── routes/  storage/  vendor/
│       │   ├── .env                     # ★ Único arquivo NÃO versionado, criado no servidor
│       │   ├── public/
│       │   │   ├── build/               # Versionado, vem do repo
│       │   │   └── storage -> ../storage/app/public  (symlink)
│       │   └── artisan
│       └── public_html/                 # ★ DocumentRoot servido pelo LiteSpeed
│           ├── index.php                # Symlink ou cópia apontando pra laravel/public/index.php
│           ├── .htaccess
│           └── (assets servidos via symlink/redirect pra ../laravel/public/)
```

**Observação**: a Hostinger não permite `DocumentRoot` arbitrário em planos compartilhados. Tipicamente o `public_html/.htaccess` faz rewrite pra `../laravel/public/index.php`, ou os arquivos de `laravel/public/` são linkados/copiados em `public_html/`.

### Variáveis de ambiente da produção (.env)

Valores reais ficam **só no servidor**. Nunca commitar. Pelo menos:

- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://eu.gabrielsv.com`
- `DB_*` apontando pro MySQL da Hostinger (host, database, user, password fornecidos no painel hPanel)
- `CACHE_DRIVER=file`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync` (Redis não disponível no shared)
- `MAIL_*` via SMTP da Hostinger ou serviço externo
- `GA_MEASUREMENT_ID=G-YYD5Q9MDYZ`, `GOOGLE_ADS_CLIENT_ID=ca-pub-…`
- `TURNSTILE_SITE_KEY=…`, `TURNSTILE_SECRET_KEY=…` (widget dedicado pra `eu.gabrielsv.com`)
- `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` (usados só pelo `AdminUserSeeder` na 1ª subida)
- `CONTACT_UA="gabrielsv-portfolio/1.0 (+https://gabrielsv.com)"`

### Deploy padrão

O usuário deploya manualmente via SSH:

```bash
cd ~/domains/eu.gabrielsv.com/laravel && \
  git pull && \
  php artisan migrate --force && \                # se houver migration
  php artisan db:seed --class=SitePagesSeeder --force && \   # se necessário
  php artisan view:clear && \
  php artisan route:clear && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache
```

**Importante**:
- Não rodar `composer install` nem `npm install` no servidor — `vendor/` e `public/build/` já vêm do repo.
- `php artisan storage:link` foi feito uma vez no setup inicial.
- Para resetar config após mexer no `.env`: `php artisan config:cache`.

### Particularidades

- **Sem Redis**: usar `file` drivers em prod (já é o padrão do `.env.example`).
- **Cron jobs**: configurados no hPanel, apontam pra `php ~/domains/eu.gabrielsv.com/laravel/artisan schedule:run`.
- **PHP CLI**: a Hostinger expõe o binário PHP correto via `php` (8.5.4). Confirmar com `php -v` na primeira sessão SSH.
- **Logs**: `~/domains/eu.gabrielsv.com/laravel/storage/logs/laravel.log`.
- **Admin**: criado via `AdminUserSeeder` lendo `ADMIN_*` do `.env`. Para criar/resetar: `php artisan db:seed --class=AdminUserSeeder --force`.

### AdSense + Analytics

- AdSense Auto Ads carrega só em `index`, `blog.*`, `tools.*` (view composer em `AppServiceProvider`).
- GA4 carrega em todo o site público; bloqueado em `filament.*` (admin).
- Pra adicionar ou remover páginas do escopo, editar `View::composer('partials.adsense', ...)` ou `'partials.google-analytics'`.

## Problemas Conhecidos / Erros Identificados

### Corrigidos ✅

- ~~CSS Global Override: `svg { width: 1.2rem; height: 1.2rem }`~~ — removido.
- ~~`vite.config.js` ignore array errado na `getFiles()`~~ — removido.
- ~~LoremService quantity mismatch (gerava 3, mostrava 5)~~ — ajustado.
- ~~Lorem Generator usava classes Font Awesome no layout Lucide~~ — corrigido.
- ~~BlogHelper usava `env()` como fallback~~ — usa apenas `config()`.
- ~~Tags multi-create no admin: race do `createOptionUsing`~~ — virou TextInput separado `new_tags`.
- ~~AdSense rejeitado por falta de `/contato`~~ — feature completa com Turnstile + Filament admin.
- ~~Outlook AADSTS90015 no link da tool ICS~~ — base oficial `/calendar/deeplink/compose` (sem `/0/`).

### Mantidos por design

- **`.flex { flex-wrap: wrap }` global**: intencional para responsividade.
- **`/public/static/` no `.gitignore`**: contém diplomas e documentos pessoais — **NUNCA commitar**.

### Pendentes

- `.env.example` não inclui `REDIS_*` no detalhe, mas como prod usa `file`, baixo impacto.
- View Components com construtores vazios (props via `@props` no Blade) — funciona, não é o padrão Laravel canonical, mas é aceito.
- Docker compose ainda usa `mysql:5.7.22` (EOL out/2023). Considerar 8.x.
- README.md ainda é o template padrão Laravel — substituir.

## Padrões para Novas Ferramentas

Checklist ao adicionar uma tool nova:

1. **Service** (se backend): `app/Services/NomeDaFerramentaService.php`.
2. **Controller**: método em `ToolsController.php`.
3. **Rota**: `routes/web.php` dentro do grupo `tools.` (name `tools.<slug>`).
4. **TOOLS array**: entrada em `AppServiceProvider::TOOLS` (route, routeMatch, slug, name, description, icon, color).
5. **View**: `resources/views/tools/<slug>.blade.php` (`@extends('layouts.tools')`).
6. **JS**: `resources/js/tools/<slug>.js` (classe ES6, auto-injetado pelo Vite). Usa `[data-tool="<slug>"]` como root.
7. **Utils**: reaproveitar `copyText` e `showToast` de `resources/js/utils/`.
8. **Lucide**: registrar o ícone em `resources/js/lucide-icons.js` (PascalCase).
9. **SEO**: `@section('title')`, `@section('description')`, `@section('tool_name')`.
10. **Preline**: usar `hs-dropdown` para selects (não `<select>` nativo — quebra a estética da suite).
11. **Toast**: já é singleton via `showToast(...)`, não precisa markup na view.

## Padrões para conteúdo editável no admin

Páginas mosaico (`/sobre`, `/uses`, `/now`, `/contato`) são editáveis em `/console` via `SitePageResource`:

- Slugs reservados: `['sobre', 'uses', 'now', 'contato']` — slug fica read-only no form.
- Campo `address` só aparece quando `slug === 'contato'`.
- Body é RichEditor (Filament) salvando HTML em `site_pages.body_html`.
- Renderização em `resources/views/pages/site-page.blade.php` decide bloco social/contact-form conforme slug.

## Comandos Úteis (local)

```bash
# Dev (Laravel serve + Queue + Pail + Vite, tudo junto)
composer dev

# Setup inicial
composer setup

# Docker (MySQL + Redis + Nginx + PHP + Mailpit)
docker compose up -d

# Build front
npm run build

# Audit de ícones Lucide usados vs registrados
rg 'data-lucide="([a-z0-9-]+)"' -or '$1' --no-filename | sort -u
```

## Idioma e estilo

- Projeto e documentação predominantemente em **português brasileiro** (pt-BR).
- Nomes de variáveis, classes e métodos em **inglês**.
- Em texto pt-BR: **sem travessão (—)**. Usar dois-pontos, parênteses ou ponto.
