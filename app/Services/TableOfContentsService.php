<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

class TableOfContentsService
{
    /**
     * Extrai H2/H3 do HTML, adiciona IDs aos headings e devolve TOC + HTML modificado.
     *
     * @return array{toc: array<int, array{id: string, text: string, level: int}>, html: string}
     */
    public function extract(?string $html): array
    {
        if (! $html || trim($html) === '') {
            return ['toc' => [], 'html' => (string) $html];
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $wrapped = '<?xml encoding="UTF-8"><div id="__toc_root">'.$html.'</div>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $headings = $xpath->query('//h2 | //h3');

        if ($headings === false || $headings->length === 0) {
            return ['toc' => [], 'html' => $html];
        }

        $usedIds = [];
        $toc = [];

        foreach ($headings as $heading) {
            if (! $heading instanceof DOMElement) {
                continue;
            }

            $text = trim($heading->textContent);
            if ($text === '') {
                continue;
            }

            $id = $heading->getAttribute('id');
            if ($id === '') {
                $id = $this->uniqueSlug($text, $usedIds);
                $heading->setAttribute('id', $id);
            }
            $usedIds[$id] = true;

            $toc[] = [
                'id' => $id,
                'text' => $text,
                'level' => (int) substr($heading->nodeName, 1),
            ];
        }

        $root = $dom->getElementById('__toc_root');
        $modifiedHtml = '';
        if ($root) {
            foreach ($root->childNodes as $child) {
                $modifiedHtml .= $dom->saveHTML($child);
            }
        } else {
            $modifiedHtml = $html;
        }

        return ['toc' => $toc, 'html' => $modifiedHtml];
    }

    private function uniqueSlug(string $text, array $used): string
    {
        $base = Str::slug($text);
        if ($base === '') {
            $base = 'secao';
        }

        $id = $base;
        $i = 2;
        while (isset($used[$id])) {
            $id = $base.'-'.$i++;
        }

        return $id;
    }
}
