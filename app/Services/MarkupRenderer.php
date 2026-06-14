<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;

class MarkupRenderer
{
    /**
     * Pós-processa o HTML do post: syntax highlight + copy buttons + callouts.
     */
    public function render(?string $html): string
    {
        if (! $html || trim($html) === '') {
            return (string) $html;
        }

        $html = $this->normalizeWordPressCodeBlocks($html);
        // Charts saem antes do highlight: um gráfico é um <pre data-chart> e, se
        // passasse pelo highlightCodeBlocks, viraria bloco de código em vez de
        // gráfico.
        $html = $this->renderCharts($html);
        $html = $this->highlightCodeBlocks($html);

        return $this->renderCallouts($html);
    }

    /**
     * Converte o marcador de gráfico num container pronto pra hidratação do
     * Chart.js no cliente, que é quem desenha de fato. O servidor não renderiza
     * imagem: emite só o <figure> com o JSON e uma descrição textual no
     * aria-label (acessibilidade/SEO). JSON inválido: o marcador é removido sem
     * quebrar a página.
     *
     * Dois formatos aceitos:
     *   - Novo:    <pre data-chart>{json}</pre>        (JSON no conteúdo)
     *   - Legado:  <div data-chart='{json}'></div>    (JSON no atributo)
     */
    private function renderCharts(string $html): string
    {
        if (! str_contains($html, 'data-chart')) {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//pre[@data-chart] | //div[@data-chart]');

        if ($nodes === false || $nodes->length === 0) {
            return $html;
        }

        // Itera numa cópia: vamos substituir nós durante o laço.
        foreach (iterator_to_array($nodes) as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            // No <pre> o JSON é o conteúdo; no <div> legado é o atributo.
            $json = $node->nodeName === 'pre'
                ? trim($node->textContent)
                : $node->getAttribute('data-chart');

            try {
                $chart = \App\Services\Charts\ChartData::fromJson($json);
            } catch (\Throwable) {
                // Marcador malformado: remove pra não poluir a página.
                $node->parentNode?->removeChild($node);

                continue;
            }

            $figure = $dom->createElement('figure');
            $figure->setAttribute('class', 'chart');
            // Container que o Chart.js hidrata (resources/js/blog/chart.js).
            $figure->setAttribute('data-chart', $json);
            // Descrição textual pros bots/leitores de tela (não há SVG nem imagem
            // no servidor; o título do gráfico já entra aqui via ariaLabel()).
            $figure->setAttribute('role', 'img');
            $figure->setAttribute('aria-label', $chart->ariaLabel());

            $node->parentNode?->replaceChild($figure, $node);
        }

        $root = $dom->getElementById('__root');
        if (! $root) {
            return $html;
        }

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    /**
     * Posts importados do WordPress vêm com wrapper <div class="wp-block-kevinbatdorf-code-block-pro">
     * contendo um <span> com a label de linguagem (com style inline) e o <pre><code>. Achatar para
     * <pre data-language="X"> para depois passar pelo pipeline padrão.
     */
    private function normalizeWordPressCodeBlocks(string $html): string
    {
        if (! str_contains($html, 'wp-block-kevinbatdorf-code-block-pro')) {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $wrappers = $xpath->query('//div[contains(concat(" ", normalize-space(@class), " "), " wp-block-kevinbatdorf-code-block-pro ")]');

        if ($wrappers === false || $wrappers->length === 0) {
            return $html;
        }

        foreach ($wrappers as $wrapper) {
            if (! $wrapper instanceof DOMElement) {
                continue;
            }

            $pre = null;
            foreach ($xpath->query('.//pre', $wrapper) as $candidate) {
                if ($candidate instanceof DOMElement) {
                    $pre = $candidate;
                    break;
                }
            }

            if (! $pre) {
                continue;
            }

            $language = null;
            foreach ($xpath->query('./span', $wrapper) as $label) {
                if (! $label instanceof DOMElement) {
                    continue;
                }
                $text = trim($label->textContent);
                if ($text !== '' && mb_strlen($text) <= 20) {
                    $language = strtolower($text);
                    break;
                }
            }

            $newPre = $dom->createElement('pre');
            $codeEl = null;
            foreach ($pre->childNodes as $child) {
                if ($child instanceof DOMElement && $child->nodeName === 'code') {
                    $codeEl = $child;
                    break;
                }
            }
            $rawCode = $codeEl ? $codeEl->textContent : $pre->textContent;
            $newCode = $dom->createElement('code', htmlspecialchars($rawCode, ENT_QUOTES, 'UTF-8'));
            if ($language) {
                $newCode->setAttribute('class', 'language-'.$language);
                $newPre->setAttribute('data-language', $language);
            }
            $newPre->appendChild($newCode);

            $wrapper->parentNode->replaceChild($newPre, $wrapper);
        }

        $root = $dom->getElementById('__root');
        if (! $root) {
            return $html;
        }

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private function highlightCodeBlocks(string $html): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $pres = $xpath->query('//pre');

        if ($pres === false || $pres->length === 0) {
            return $html;
        }

        foreach ($pres as $pre) {
            if (! $pre instanceof DOMElement) {
                continue;
            }

            $codeEl = null;
            foreach ($pre->childNodes as $child) {
                if ($child instanceof DOMElement && $child->nodeName === 'code') {
                    $codeEl = $child;
                    break;
                }
            }

            $rawCode = $codeEl ? $codeEl->textContent : $pre->textContent;
            // Só destaca quando a linguagem é explícita (class="language-X" no
            // <code>/<pre> ou data-language). Sem isso fica null: o bloco sai
            // como texto puro, sem auto-detecção por conteúdo (que chutava
            // errado). A tokenização é no cliente, via highlight.js
            // (resources/js/blog/highlight.js).
            $language = $this->detectLanguage($pre, $codeEl);

            $filename = $pre->getAttribute('data-filename');

            $wrapper = $dom->createElement('div');
            $wrapper->setAttribute('class', 'code-block'.($language ? ' code-block--'.$language : ''));

            if ($filename !== '' || $language) {
                $header = $dom->createElement('div');
                $header->setAttribute('class', 'code-block__header');
                if ($filename !== '') {
                    $name = $dom->createElement('span', htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'));
                    $name->setAttribute('class', 'code-block__filename');
                    $header->appendChild($name);
                } else {
                    // espaçador para empurrar o lang à direita quando não há filename
                    $spacer = $dom->createElement('span', '');
                    $spacer->setAttribute('class', 'code-block__filename');
                    $spacer->setAttribute('aria-hidden', 'true');
                    $header->appendChild($spacer);
                }
                if ($language) {
                    $lang = $dom->createElement('span', strtoupper($language));
                    $lang->setAttribute('class', 'code-block__lang');
                    $header->appendChild($lang);
                }
                $wrapper->appendChild($header);
            }

            $body = $dom->createElement('div');
            $body->setAttribute('class', 'code-block__body');

            $newPre = $dom->createElement('pre');
            $newCode = $dom->createElement('code');
            if ($language) {
                // O highlight.js lê esta classe e tokeniza no cliente. O servidor
                // emite só o código escapado (DOMDocument escapa no saveHTML),
                // que também é o fallback legível sem JS.
                $newCode->setAttribute('class', 'language-'.$language);
            }
            $newCode->appendChild($dom->createTextNode($rawCode));
            $newPre->appendChild($newCode);
            $body->appendChild($newPre);

            $copy = $dom->createElement('button', 'Copiar');
            $copy->setAttribute('type', 'button');
            $copy->setAttribute('class', 'code-block__copy');
            $copy->setAttribute('data-copy', '');
            $copy->setAttribute('aria-label', 'Copiar código');
            $body->appendChild($copy);

            $wrapper->appendChild($body);

            $pre->parentNode->replaceChild($wrapper, $pre);
        }

        $root = $dom->getElementById('__root');
        if (! $root) {
            return $html;
        }

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private function detectLanguage(DOMElement $pre, ?DOMElement $code): ?string
    {
        $candidate = $code?->getAttribute('class') ?? '';
        if (preg_match('/language-([a-z0-9+\-]+)/i', $candidate, $m)) {
            return strtolower($m[1]);
        }

        $candidate = $pre->getAttribute('class');
        if (preg_match('/language-([a-z0-9+\-]+)/i', $candidate, $m)) {
            return strtolower($m[1]);
        }

        $dataLang = $pre->getAttribute('data-language');
        if ($dataLang !== '') {
            return strtolower($dataLang);
        }

        return null;
    }

    private function renderCallouts(string $html): string
    {
        // Padrão: <blockquote class="callout-info">...</blockquote> → <div class="callout callout--info">...</div>
        $types = ['info', 'warn', 'tip', 'danger'];
        $icons = [
            'info' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>',
            'warn' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4M12 17h.01"/></svg>',
            'tip' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.8.8 1.3 1.5 1.5 2.5"/><path d="M9 18h6M10 22h4"/></svg>',
            'danger' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>',
        ];

        foreach ($types as $type) {
            $html = preg_replace_callback(
                '/<blockquote([^>]*)class="([^"]*\b)?callout-'.$type.'(\b[^"]*)?"([^>]*)>(.*?)<\/blockquote>/is',
                function ($m) use ($type, $icons) {
                    return '<div class="callout callout--'.$type.'" role="note"><div class="callout__icon" aria-hidden="true">'.$icons[$type].'</div><div class="callout__body">'.$m[5].'</div></div>';
                },
                $html
            );
        }

        return $html;
    }
}
