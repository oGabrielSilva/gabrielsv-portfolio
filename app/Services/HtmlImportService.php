<?php

namespace App\Services;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Sanitiza HTML colado no import de posts.
 *
 * O fluxo: o admin cola o HTML completo do artigo (com os <div data-chart>
 * já embutidos) num modal e isso vira o body_html, SEM passar pelo TipTap do
 * RichEditor (que descartaria o data-chart no reparse).
 *
 * Como o HTML é não-confiável, ele é sanitizado aqui contra XSS com uma
 * allowlist explícita: mantém o que os posts do blog usam (texto, headings,
 * tabelas, listas, code blocks, links, imagens e o marcador data-chart) e
 * remove tudo que executa (script, on*, iframe, etc.). Estilos inline (style)
 * também são descartados: o blog renderiza com o próprio design system.
 */
class HtmlImportService
{
    public function sanitize(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        return $this->sanitizer()->sanitize($html);
    }

    private function sanitizer(): HtmlSanitizer
    {
        $config = (new HtmlSanitizerConfig)
            // Conjunto seguro de elementos (texto, headings, listas, tabelas,
            // pre/code, blockquote, etc.) e links/mídias relativas.
            ->allowSafeElements()
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            // Atributos que o blog usa na renderização.
            ->allowAttribute('class', allowedElements: '*')
            // id só em headings: é o que o sumário (TableOfContentsService) usa
            // como âncora. Liberar id em '*' deixaria o conteúdo importado colidir
            // com ids da própria página (ex.: o wrapper __root do MarkupRenderer
            // ou __toc_root do TOC, que são resolvidos por getElementById).
            ->allowAttribute('id', allowedElements: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])
            // Sem 'style' inline: o blog tem CSS próprio e style em '*' é
            // superfície de CSS injection/clickjacking. O gráfico não precisa de
            // style (vem no data-chart); o resto herda o design system.
            // Marcador de gráfico: <pre data-chart>{json}</pre> (novo formato) ou
            // <div data-chart='{json}'> (legado). O MarkupRenderer converte os dois.
            ->allowAttribute('data-chart', allowedElements: ['div', 'pre'])
            ->allowAttribute('data-language', allowedElements: 'pre')
            ->allowAttribute('data-filename', allowedElements: 'pre')
            // Atributos de tabela usados por conteúdo importado.
            ->allowAttribute('colspan', allowedElements: ['td', 'th'])
            ->allowAttribute('rowspan', allowedElements: ['td', 'th'])
            // <div> não está no conjunto "safe" por padrão; o data-chart é um div.
            ->allowElement('div', ['class', 'data-chart'])
            ->allowElement('figure', ['class'])
            ->allowElement('figcaption', ['class'])
            // Limite generoso pra artigos longos (default do Filament é 500k).
            ->withMaxInputLength(800_000);

        // Defesa extra: descarta (tag + conteúdo) o que nunca deve executar ou
        // renderizar. dropElement remove inclusive os filhos — diferente de
        // blockElement, que manteria o texto interno do <script> como nó solto.
        // allowSafeElements já não inclui esses, mas deixar explícito documenta
        // a intenção e protege caso a allowlist mude.
        $config = $config
            ->dropElement('script')
            ->dropElement('iframe')
            ->dropElement('object')
            ->dropElement('embed')
            ->dropElement('style')
            ->dropElement('canvas');

        return new HtmlSanitizer($config);
    }
}
