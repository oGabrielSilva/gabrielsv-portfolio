/**
 * Syntax highlight dos posts do blog, no cliente, via highlight.js.
 *
 * Carregado APENAS nas páginas de post que têm código (blog/show empurra este
 * entrypoint via @push('scripts') só quando $hasCode).
 *
 * O servidor (MarkupRenderer) emite só o código escapado dentro de
 * <pre><code class="language-X">…</code></pre>. Aqui tokenizamos no cliente.
 * Sem auto-detecção: um bloco só é destacado quando tem class="language-X" com
 * uma linguagem registrada abaixo; o resto fica texto puro (legível, sem cor
 * errada).
 *
 * Para adicionar uma linguagem: importe o módulo e registre no objeto `langs`.
 * Cada módulo já traz seus próprios aliases (js→javascript, ts→typescript,
 * py→python, html→xml, sh→bash, cs/c#→csharp, rb→ruby, etc.).
 */
import hljs from "highlight.js/lib/core";

import bash from "highlight.js/lib/languages/bash";
import c from "highlight.js/lib/languages/c";
import cpp from "highlight.js/lib/languages/cpp";
import csharp from "highlight.js/lib/languages/csharp";
import css from "highlight.js/lib/languages/css";
import diff from "highlight.js/lib/languages/diff";
import dockerfile from "highlight.js/lib/languages/dockerfile";
import go from "highlight.js/lib/languages/go";
import ini from "highlight.js/lib/languages/ini";
import java from "highlight.js/lib/languages/java";
import javascript from "highlight.js/lib/languages/javascript";
import json from "highlight.js/lib/languages/json";
import kotlin from "highlight.js/lib/languages/kotlin";
import makefile from "highlight.js/lib/languages/makefile";
import markdown from "highlight.js/lib/languages/markdown";
import nginx from "highlight.js/lib/languages/nginx";
import php from "highlight.js/lib/languages/php";
import phpTemplate from "highlight.js/lib/languages/php-template";
import powershell from "highlight.js/lib/languages/powershell";
import python from "highlight.js/lib/languages/python";
import ruby from "highlight.js/lib/languages/ruby";
import rust from "highlight.js/lib/languages/rust";
import scss from "highlight.js/lib/languages/scss";
import shell from "highlight.js/lib/languages/shell";
import sql from "highlight.js/lib/languages/sql";
import swift from "highlight.js/lib/languages/swift";
import typescript from "highlight.js/lib/languages/typescript";
import xml from "highlight.js/lib/languages/xml";
import yaml from "highlight.js/lib/languages/yaml";

const langs = {
    bash,
    c,
    cpp,
    csharp,
    css,
    diff,
    dockerfile,
    go,
    ini,
    java,
    javascript,
    json,
    kotlin,
    makefile,
    markdown,
    nginx,
    php,
    "php-template": phpTemplate,
    powershell,
    python,
    ruby,
    rust,
    scss,
    shell,
    sql,
    swift,
    typescript,
    xml,
    yaml,
};

for (const [name, mod] of Object.entries(langs)) {
    hljs.registerLanguage(name, mod);
}

// Nosso conteúdo já vem escapado do servidor; silencia o aviso de innerHTML.
hljs.configure({ ignoreUnescapedHTML: true });

function run() {
    document
        .querySelectorAll("pre code[class*='language-']")
        .forEach((el) => {
            if (el.dataset.highlighted) return;

            const match = el.className.match(/language-([\w+-]+)/i);
            const lang = match && match[1].toLowerCase();

            // Só destaca linguagens registradas (por nome ou alias). Desconhecida
            // fica texto puro, em vez de auto-detectar (que erra).
            if (lang && hljs.getLanguage(lang)) {
                hljs.highlightElement(el);
            }
        });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", run);
} else {
    run();
}
