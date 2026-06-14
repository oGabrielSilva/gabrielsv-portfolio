# Blocos de código nos posts do blog

Como inserir código com syntax highlight. Pensado para **agentes de IA**
gerarem o HTML do artigo já com os blocos prontos.

## Como funciona (resumo)

- Um bloco de código é `<pre><code class="language-X">…</code></pre>`.
- O `X` em `class="language-X"` é o slug da linguagem (lista abaixo).
- O servidor (`MarkupRenderer`) só envolve o bloco no visual (header, label,
  botão copiar) e emite o código **escapado**. A tokenização (as cores) é feita
  no cliente pelo **highlight.js**, carregado só nos posts que têm código.
- **Não há auto-detecção**: sem `class="language-X"` o bloco fica texto puro
  (legível, sem cor). É de propósito, pra não chutar linguagem errada.

## Regras

- Escape `<`, `>` e `&` dentro do código como `&lt;`, `&gt;`, `&amp;`.
- O slug vai em `class="language-X"` no `<code>` (ou no `<pre>`). Também aceita
  `data-language="X"` no `<pre>`.
- Opcional: `data-filename="exemplo.ext"` no `<pre>` exibe o nome do arquivo no
  header.
- Slug desconhecido (fora da lista) cai em texto puro, sem erro.

## Exemplo

```html
<pre><code class="language-go">package main

import "fmt"

func main() {
    fmt.Println("oi")
}</code></pre>
```

## Linguagens suportadas

Slug (e aliases aceitos):

| Linguagem    | Slug          | Aliases            |
|--------------|---------------|--------------------|
| JavaScript   | `javascript`  | `js`, `jsx`        |
| TypeScript   | `typescript`  | `ts`, `tsx`        |
| Python       | `python`      | `py`               |
| Go           | `go`          |                    |
| Rust         | `rust`        |                    |
| Java         | `java`        |                    |
| C#           | `csharp`      | `cs`, `c#`         |
| PHP          | `php`         |                    |
| Ruby         | `ruby`        | `rb`               |
| C            | `c`           |                    |
| C++          | `cpp`         | `c++`, `cc`        |
| Kotlin       | `kotlin`      |                    |
| Swift        | `swift`       |                    |
| Bash         | `bash`        | `sh`               |
| Shell        | `shell`       | `console`          |
| PowerShell   | `powershell`  | `ps`, `ps1`        |
| SQL          | `sql`         |                    |
| JSON         | `json`        |                    |
| YAML         | `yaml`        | `yml`              |
| HTML/XML     | `xml`         | `html`, `svg`      |
| CSS          | `css`         |                    |
| SCSS         | `scss`        |                    |
| Dockerfile   | `dockerfile`  | `docker`           |
| INI/TOML     | `ini`         | `toml`             |
| Diff         | `diff`        | `patch`            |
| Markdown     | `markdown`    | `md`               |
| Makefile     | `makefile`    | `mk`               |
| Nginx        | `nginx`       |                    |

Para adicionar uma linguagem nova: importe o módulo em
`resources/js/blog/highlight.js`, registre no objeto `langs` e rode `npm run
build`.

## Instrução pronta para um agente

> Todo bloco de código deve vir como `<pre><code class="language-SLUG">…</code></pre>`,
> com o SLUG da linguagem (ex.: `javascript`, `typescript`, `python`, `go`,
> `rust`, `java`, `csharp`, `php`, `ruby`, `bash`, `sql`, `json`, `yaml`,
> `xml`). Escape `<`, `>` e `&` como `&lt;`, `&gt;`, `&amp;` no conteúdo.
> Opcional: `data-filename="exemplo.ext"` no `<pre>`. Nunca deixe o bloco sem
> `class="language-…"`: sem isso o destaque vira texto puro.
