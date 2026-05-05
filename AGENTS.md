# AGENTS.md — gabrielsv.com/portfolio

Instruções para agentes de IA que atuam neste repositório.

## Visão Geral do Projeto

Portfolio pessoal e suíte de ferramentas online de **Gabriel Henrique da Silva**, desenvolvedor fullstack. O projeto combina uma landing page de portfolio com um conjunto crescente de ferramentas utilitárias focadas em SEO e utilidade para desenvolvedores brasileiros.

## Stack Técnico

| Camada     | Tecnologia                                       |
| ---------- | ------------------------------------------------ |
| Backend    | **Laravel 12** (PHP 8.2+)                        |
| Frontend   | **Blade** + **Tailwind CSS v4** + **Vanilla JS** |
| Build      | **Vite 7** com `laravel-vite-plugin`             |
| UI Library | **Preline UI** (dropdowns, componentes)          |
| Ícones     | **Lucide** (tools) / **Font Awesome** (homepage) |
| Animações  | **AOS** (Animate On Scroll)                      |
| Database   | MySQL 5.7 (Docker) / SQLite (local dev)          |
| Cache      | Redis                                            |
| Infra      | Docker Compose (Nginx + PHP-FPM + MySQL + Redis) |

## Estrutura de Diretórios

```
portfolio/
├── app/
│   ├── Http/Controllers/    # Controllers (Home, Tools, BrandGuide, CardGenerator)
│   ├── Models/              # Eloquent models (apenas User)
│   ├── Providers/           # AppServiceProvider (registra TOOLS e View::share)
│   ├── Services/            # Lógica de negócio (UuidService, LoremService)
│   ├── Utils/               # Helpers (BlogHelper)
│   └── View/Components/     # Blade component classes (AboutSection, BlogPostCard, ServiceCard)
├── resources/
│   ├── views/
│   │   ├── layouts/         # app.blade.php (homepage), tools.blade.php (ferramentas)
│   │   ├── partials/        # head.blade.php, footer.blade.php
│   │   ├── components/      # Blade components (about-section, blog-post-card, service-card)
│   │   ├── tools/           # Views individuais de cada ferramenta
│   │   ├── index.blade.php  # Homepage
│   │   ├── brand-guide.blade.php
│   │   └── card-generator.blade.php
│   ├── js/
│   │   ├── app.js           # Entry point (AOS, Preline, mobile menu)
│   │   ├── bootstrap.js     # Axios setup
│   │   ├── card-generator.js
│   │   └── tools/           # JS por ferramenta (ES6 classes, Vanilla JS)
│   └── css/
│       └── app.css          # Tailwind v4 + @theme tokens + service card variants
├── routes/web.php           # Todas as rotas (homepage, tools, brand-guide, card-generator)
├── config/                  # Laravel configs (app.php inclui owner_blog_url)
├── docker/                  # Dockerfile PHP, Nginx config
├── compose.yml              # Docker Compose (app, nginx, db, phpmyadmin, redis)
├── BRAND_GUIDE.md           # Design system (cores, tipografia, espaçamento)
├── TOOLS_ROADMAP.md         # Checklist de ferramentas (concluídas e pendentes)
└── plan-tools.md            # Planejamento detalhado de ferramentas
```

## Convenções do Projeto

### Backend (PHP/Laravel)

- **Controllers** são simples — delegam lógica para `Services/`.
- **Services** (`app/Services/`) encapsulam lógica de negócio reutilizável.
- **TOOLS** é uma constante em `AppServiceProvider` com metadata de cada ferramenta (route, slug, name, icon, color). É compartilhada com todas as views via `View::share`.
- Rotas da homepage: `/`, `/brand-guide`, `/card-generator`.
- Rotas das tools: agrupadas sob `/tools` com prefixo `tools.`.
- UUID Generator possui URLs SEO-friendly: `/tools/uuid/{type}` (ex: `uuid-v4`, `cuid`, `nanoid`).
- CPF/CNPJ redireciona `/tools/cpf-cnpj` → `/tools/cpf` e usa `defaults('type', 'cpf')`.

### Frontend (JS)

- Cada ferramenta tem sua **classe ES6** em `resources/js/tools/`.
- Ferramentas 100% client-side: Percentage, Image Compressor, CPF/CNPJ, Base64, Slugify, JSON Formatter, Cron Explainer, Markdown Preview, World Clock, Keyboard Tester, Password Generator, Color Picker.
- Ferramentas com backend (AJAX POST): UUID Generator, Lorem Ipsum.
- `app.js` inicializa AOS, Preline e o toggle do menu mobile.
- Scripts de ferramentas são injetados via `@push('scripts')` + `@vite(...)`.

### Estilos (CSS/Tailwind)

- Tailwind CSS **v4** com `@import "tailwindcss"` (não usa `tailwind.config.js`).
- Design tokens definidos via `@theme {}` no `app.css`.
- Cores custom: `bulma-primary` (#00d1b2), `bulma-link`, `bulma-dark`.
- Variantes de cor para `.service-card--{color}` definidas manualmente no `app.css`.
- Dark mode é o padrão (sem toggle de light mode implementado).

### Layouts

- **`layouts/app.blade.php`**: Homepage e páginas gerais. Usa Font Awesome.
- **`layouts/tools.blade.php`**: Ferramentas. Usa Lucide icons. Inclui sidebar desktop + menu mobile com listagem de tools.

### Vite

- `vite.config.js` injeta automaticamente todos os JS de `resources/js/tools/` via `getFiles()`.
- Entrypoints fixos: `app.css`, `app.js`, `card-generator.js`.

## Problemas Conhecidos / Erros Identificados

### Corrigidos ✅

- ~~CSS Global Override: `svg { width: 1.2rem; height: 1.2rem }`~~ — Removido do `app.css`
- **CSS Global**: `.flex { flex-wrap: wrap }` é **intencional** para responsividade — NÃO remover.
- ~~`vite.config.js` ignore array errado na `getFiles()`~~ — Array removido
- ~~LoremService quantity mismatch (gerava 3, mostrava 5)~~ — Ajustado para gerar 5
- ~~Lorem Generator usava classes Font Awesome no layout Lucide (ícone spin infinito)~~ — Corrigido para usar `classList.add/remove('animate-spin')`; adicionado check `response.ok`
- ~~BlogHelper usava `env()` como fallback~~ — Removido, usa apenas `config()`
- ~~`Illuminate\Http\Request` importado mas não usado no `HomeController`~~ — Removido

### Pendentes

#### `.env` comitado no repositório (SEGURANÇA)
O arquivo `.env` contém chaves reais (APP_KEY, Google Ads Client ID). Verificar se foi comitado antes de o `.gitignore` existir.

#### `.env` vs `.env.example` — divergência significativa (MÉDIO)
- `.env` usa `SESSION_DRIVER=redis`, `QUEUE_CONNECTION=redis`, `CACHE_DRIVER=redis`.
- `.env.example` usa `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`, `CACHE_DRIVER=file`.
- `.env.example` não inclui `REDIS_*` configs nem `BCRYPT_ROUNDS`.

#### View Components sem props no constructor (BAIXO)
`AboutSection.php`, `BlogPostCard.php`, `ServiceCard.php` — construtores vazios, props declarados apenas no Blade via `@props`. Funciona, mas não segue o padrão Laravel.

#### Docker compose — MySQL 5.7 defasado (INFO)
`compose.yml` usa `mysql:5.7.22` (EOL outubro 2023). Considerar migração para MySQL 8.x.

#### README.md é o template padrão do Laravel (INFO)
Deveria ser substituído por um README customizado do projeto.

## Padrões para Novas Ferramentas

Ao adicionar uma nova ferramenta, siga este checklist:

1. **Service** (se necessário): Criar em `app/Services/NomeDaFerramentaService.php`
2. **Controller**: Adicionar método em `ToolsController.php`
3. **Rota**: Adicionar em `routes/web.php` dentro do grupo `tools`
4. **TOOLS array**: Adicionar entrada em `AppServiceProvider::TOOLS`
5. **View**: Criar `resources/views/tools/nome.blade.php` (extends `layouts.tools`)
6. **JS**: Criar `resources/js/tools/nome.js` (classe ES6, auto-injetado pelo Vite)
7. **SEO**: Usar `@section('title')`, `@section('description')`, `@section('tool_name')`
8. **Toast**: Incluir markup do toast para feedback de cópia
9. **Preline**: Usar `hs-dropdown` para dropdowns

## Comandos Úteis

```bash
# Dev (Laravel serve + Queue + Pail + Vite, tudo junto)
composer dev

# Setup inicial
composer setup

# Docker
docker compose up -d

# Build front
npm run build
```

## Idioma

O projeto e sua documentação são predominantemente em **português brasileiro** (pt-BR). Nomes de variáveis, classes e métodos seguem convenção em **inglês**. Ao contribuir, manter este padrão.
