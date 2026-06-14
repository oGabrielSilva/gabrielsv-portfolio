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

    /**
     * Aliases pra linguagens que tempest/highlight v2 não conhece nativamente.
     * Mapeia pro highlighter mais próximo (não é 100% correto mas dá cor decente).
     * Quando migrarmos pra Shiki essa tabela some.
     */
    private const LANGUAGE_ALIASES = [
        'ts' => 'js',
        'tsx' => 'js',
        'typescript' => 'js',
        'javascript' => 'js',
        'jsx' => 'js',
        'mjs' => 'js',
        'cjs' => 'js',
        'node' => 'js',
        'yaml' => 'json',
        'yml' => 'json',
        'toml' => 'json',
        'env' => 'bash',
        'dotenv' => 'bash',
        'sh' => 'bash',
        'shell' => 'bash',
        'zsh' => 'bash',
        'console' => 'bash',
        'dockerfile' => 'bash',
        'docker' => 'bash',
        'makefile' => 'bash',
        'ini' => 'bash',
        'conf' => 'bash',
        'nginx' => 'bash',
        'apache' => 'bash',
        'sql' => 'php',
        'mysql' => 'php',
        'postgres' => 'php',
        'postgresql' => 'php',
        'go' => 'php',
        'rust' => 'php',
        'python' => 'php',
        'py' => 'php',
        'ruby' => 'php',
        'rb' => 'php',
        'java' => 'php',
        'kotlin' => 'php',
        'swift' => 'php',
        'c' => 'php',
        'cpp' => 'php',
        'csharp' => 'php',
        'cs' => 'php',
    ];

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
            $language = $this->detectLanguage($pre, $codeEl);

            // Blocos criados no RichEditor do Filament não trazem class="language-X".
            // Tenta farejar a linguagem pelo conteúdo; se nada for conclusivo,
            // mantém null e cai em 'txt' (texto neutro). Não forçamos uma
            // linguagem: saída de terminal, config e prosa em <pre> sairiam
            // coloridas como código (era o bug do fallback 'js').
            if ($language === null) {
                $language = $this->detectLanguageFromCode($rawCode);
            }

            $filename = $pre->getAttribute('data-filename');

            $highlighterLang = $language ? (self::LANGUAGE_ALIASES[$language] ?? $language) : 'txt';
            try {
                $highlighted = $this->highlighter->parse($rawCode, $highlighterLang);
            } catch (\Throwable) {
                $highlighted = htmlspecialchars($rawCode, ENT_QUOTES, 'UTF-8');
            }

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

    /**
     * Fareja a linguagem pelo conteúdo do bloco quando não há class="language-X"
     * (caso dos blocos criados no RichEditor). Heurística por pistas: cada regra
     * usa marcas inequívocas da linguagem. Retorna null quando nada é
     * conclusivo, deixando o caller aplicar o default (js).
     *
     * Ordem importa: linguagens com sinais únicos (tag PHP, anotações Java,
     * package Go) vêm antes das de sintaxe mais genérica (JS).
     */
    private function detectLanguageFromCode(string $code): ?string
    {
        $code = trim($code);
        if ($code === '') {
            return null;
        }

        // PHP: tag de abertura, namespace/use, ou $variáveis com ->.
        if (preg_match('/<\?php|^\s*(namespace|use)\s+[\\\\A-Za-z]|\$\w+\s*->/m', $code)) {
            return 'php';
        }

        // JSON: começa com { ou [, tem "chave": e nenhuma marca de código.
        if (preg_match('/^\s*[\{\[]/', $code)
            && preg_match('/"[^"]+"\s*:/', $code)
            && ! preg_match('/;|=>|function|\bconst\b|\bif\b/', $code)) {
            return 'json';
        }

        // HTML/XML: começa com tag, doctype ou declaração xml.
        if (preg_match('/^\s*<(?:!doctype|\?xml|[a-z][a-z0-9-]*[\s>\/])/i', $code)) {
            return 'html';
        }

        // Java: anotações, System.out, import com ;, ou assinatura + tipo.
        if (preg_match('/@(Override|Service|Autowired|Component|Repository|SpringBootApplication|Entity)\b/', $code)
            || preg_match('/\bSystem\.out\b|\bpublic\s+static\s+void\s+main\b/', $code)
            || preg_match('/^\s*import\s+[a-z]+(\.[a-z0-9]+){2,};/m', $code)) {
            return 'java';
        }

        // C#: using namespace, atributos, Console.WriteLine, async Task.
        if (preg_match('/\bConsole\.WriteLine\b|\busing\s+System\b|\bnamespace\s+\w+\s*\{|\bpublic\s+class\s+\w+\s*:/m', $code)) {
            return 'csharp';
        }

        // Go: package + func, ou := / fmt.
        if (preg_match('/^\s*package\s+\w+/m', $code)
            && preg_match('/\bfunc\b|\bimport\s*\(|:=/', $code)) {
            return 'go';
        }

        // Rust: fn main, let mut, use ::, println!.
        if (preg_match('/\bfn\s+\w+\s*\(|\blet\s+mut\b|\bprintln!|\buse\s+\w+::/', $code)) {
            return 'rust';
        }

        // Python: def/class com :, imports, f-strings, __dunder__.
        if (preg_match('/^\s*(def|class)\s+\w+.*:\s*$|^\s*(from\s+\w+\s+import|import\s+\w+)\b|\bprint\(|__\w+__/m', $code)) {
            return 'python';
        }

        // Ruby: def...end, puts, require, símbolos :sym, blocos do |x|.
        if (preg_match('/\bdef\s+\w+.*\n.*\bend\b|\bputs\s+|\brequire\s+[\'"]|\bdo\s*\|\w+\|/s', $code)) {
            return 'ruby';
        }

        // SQL: verbos no início (case-insensitive).
        if (preg_match('/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|ALTER|DROP)\s/i', $code)) {
            return 'sql';
        }

        // Bash/shell: shebang ou comandos/CLI comuns no início de linha.
        if (preg_match('/^#!.*\b(sh|bash|zsh)\b|^\s*(npm|yarn|pnpm|composer|php artisan|docker|git|cd|sudo|apt|curl|wget|export)\s/m', $code)) {
            return 'bash';
        }

        // JS/TS: imports ES, export, const/let, arrow functions, require.
        if (preg_match('/\b(import\s+.+\s+from\s+[\'"]|export\s+(default|const|function|class)|const\s+\w+\s*=|let\s+\w+\s*=|=>|require\()/', $code)) {
            return 'js';
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
