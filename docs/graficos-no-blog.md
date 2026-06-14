# Gráficos em posts do blog (`data-chart`)

Como inserir um gráfico no corpo de um post. Pensado para **agentes de IA**
gerarem o HTML do artigo já com o gráfico pronto.

## Como funciona (resumo)

- No corpo do post (`body_html`), um gráfico é **um único elemento**:
  `<div data-chart='{...json...}'></div>`.
- `div` + atributo `data-*` passam pelo sanitizer do editor (Filament) intactos
  e **não executam nada** — não use `<script>` nem `<canvas>` no artigo.
- No servidor, esse `<div>` vira um `<figure class="chart" data-chart=...>` com
  uma descrição textual no `aria-label` (é o que o Google e leitores de tela
  leem).
- No cliente, o **Chart.js** lê o JSON e desenha o gráfico de verdade. A lib só
  é baixada em páginas que têm gráfico.

## O marcador

```html
<div data-chart='{ ...json... }'></div>
```

Regras do atributo:

- Use **aspas simples** em volta do `data-chart` e o JSON normal (aspas duplas)
  dentro. Nunca use aspas simples dentro do JSON.
- O JSON deve ser válido e em uma linha (sem quebras de linha dentro do
  atributo).
- Se o JSON for inválido, o servidor **remove o marcador** silenciosamente (o
  post não quebra, mas o gráfico não aparece).

## Schema do JSON

| Campo        | Tipo                    | Obrigatório | Padrão  | Descrição |
|--------------|-------------------------|-------------|---------|-----------|
| `type`       | string                  | não         | `"bar"` | Só `"bar"` é suportado hoje. |
| `labels`     | string[]                | **sim**     | —       | Uma categoria por barra (eixo de categorias). |
| `datasets`   | objeto[] (ver abaixo)   | **sim**     | —       | Uma ou mais séries. |
| `stacked`    | boolean                 | não         | `false` | `true` empilha as séries na mesma barra. |
| `horizontal` | boolean                 | não         | `false` | `true` = barras horizontais; `false` = verticais. |
| `xLabel`     | string                  | não         | —       | Rótulo do eixo de valores. |
| `title`      | string                  | não         | —       | Título exibido acima do gráfico. |

### `datasets[]`

| Campo   | Tipo      | Obrigatório | Descrição |
|---------|-----------|-------------|-----------|
| `label` | string    | recomendado | Nome da série (aparece na legenda e no tooltip). |
| `data`  | number[]  | **sim**     | Um valor por item de `labels`, **na mesma ordem**. |
| `color` | string    | recomendado | Cor da série em hex (`#rgb` ou `#rrggbb`). Sem cor válida, usa a paleta padrão. |

Regras importantes:

- `data` é alinhado a `labels` por **posição**. Se tiver itens a mais, o excesso
  é cortado; a menos, completa com `0`.
- `color` só aceita hex. Qualquer outro formato cai na paleta padrão.
- A legenda só aparece quando há **2+ séries**.

## Exemplos

### 1. Barras empilhadas horizontais (o caso mais comum)

```html
<div data-chart='{"type":"bar","stacked":true,"horizontal":true,"labels":["Node / better-auth","PHP / Laravel Fortify","Java / Spring Boot"],"datasets":[{"label":"Nativo / config","data":[67,22,41],"color":"#1D9E75"},{"label":"Manual","data":[0,48,197],"color":"#D85A30"}],"xLabel":"Linhas de codigo escritas pelo dev","title":"Verbosidade real: o que voce escreve"}'></div>
```

### 2. Barras verticais simples (uma série)

```html
<div data-chart='{"type":"bar","labels":["Jan","Fev","Mar","Abr"],"datasets":[{"label":"Visitas","data":[120,190,300,250],"color":"#00d1b2"}],"title":"Visitas por mes"}'></div>
```

### 3. Barras agrupadas (várias séries, lado a lado)

```html
<div data-chart='{"type":"bar","labels":["2024","2025","2026"],"datasets":[{"label":"Frontend","data":[40,55,70],"color":"#3b82f6"},{"label":"Backend","data":[60,45,30],"color":"#f59e0b"}],"xLabel":"% do tempo","title":"Divisao de esforco"}'></div>
```

## Cores sugeridas (paleta do site)

- Primária (verde): `#00d1b2`
- Verde alternativo: `#1D9E75`
- Laranja: `#D85A30`
- Azul: `#3b82f6`
- Âmbar: `#f59e0b`
- Vermelho: `#ef4444`
- Roxo: `#8b5cf6`

## Onde colocar no artigo

Insira o `<div data-chart>` como um bloco próprio, normalmente **logo após o
parágrafo que apresenta o gráfico**. Não o coloque dentro de `<p>`, `<table>`,
`<li>` ou outro elemento — ele deve ser um irmão direto dos parágrafos/headings
do corpo.

## Instrução pronta para um agente

> Ao gerar o HTML deste artigo, sempre que houver um gráfico de barras,
> insira-o como um único elemento `<div data-chart='{...}'></div>` com JSON
> válido (aspas simples por fora, aspas duplas no JSON). Use o schema: `type`
> ("bar"), `labels` (string[]), `datasets` ([{`label`, `data` number[],
> `color` hex}]), e opcionalmente `stacked`, `horizontal`, `xLabel`, `title`.
> O array `data` de cada série deve ter o mesmo tamanho e ordem de `labels`.
> Nunca use `<canvas>` nem `<script>` — só o `<div data-chart>`. Coloque-o como
> bloco próprio no corpo, não dentro de outro elemento.
