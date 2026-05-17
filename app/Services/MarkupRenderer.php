<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;

class MarkupRenderer
{
    private Highlighter $highlighter;

    public function __construct()
    {
        $this->highlighter = new Highlighter(new CssTheme);
    }

    /**
     * Pós-processa o HTML do post: syntax highlight + copy buttons + callouts.
     */
    public function render(?string $html): string
    {
        if (! $html || trim($html) === '') {
            return (string) $html;
        }

        $html = $this->highlightCodeBlocks($html);

        return $this->renderCallouts($html);
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
            $language = $this->detectLanguage($pre, $codeEl);
            $filename = $pre->getAttribute('data-filename');

            try {
                $highlighted = $this->highlighter->parse($rawCode, $language ?? 'txt');
            } catch (\Throwable) {
                $highlighted = htmlspecialchars($rawCode, ENT_QUOTES, 'UTF-8');
            }

            $wrapper = $dom->createElement('div');
            $wrapper->setAttribute('class', 'code-block'.($language ? ' code-block--'.$language : ''));

            if ($filename !== '') {
                $header = $dom->createElement('div');
                $header->setAttribute('class', 'code-block__header');
                $name = $dom->createElement('span', htmlspecialchars($filename, ENT_QUOTES, 'UTF-8'));
                $name->setAttribute('class', 'code-block__filename');
                $header->appendChild($name);
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
                $newCode->setAttribute('class', 'language-'.$language);
            }

            $fragment = $dom->createDocumentFragment();
            @$fragment->appendXML('<root>'.$highlighted.'</root>');
            if ($fragment->firstChild) {
                foreach ($fragment->firstChild->childNodes as $node) {
                    $newCode->appendChild($node->cloneNode(true));
                }
            } else {
                $newCode->appendChild($dom->createTextNode($rawCode));
            }

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
