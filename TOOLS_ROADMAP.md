# Tools Roadmap

Checklist de ferramentas a serem implementadas no projeto.

## Legenda

- [x] Concluído
- [ ] Pendente

---

## 1. Refatorar Tools Existentes

Remover Alpine.js e seguir o padrão do UUID Generator.

- [x] **UUID Generator** - Service + Vanilla JS + URLs SEO
- [ ] **Lorem Ipsum** - Criar `LoremService`, refatorar view/JS
- [ ] **Calculadora %** - Refatorar view/JS (100% frontend)
- [ ] **Compressor de Imagem** - Refatorar view/JS (100% frontend)

---

## 2. Ferramentas para Desenvolvedores (Alta Prioridade)

_Essenciais para atrair tráfego técnico e recorrência._

- [x] Gerador de UUID/ULID/CUID
- [ ] Slugify (Gerador de Slugs)
- [ ] Formatador e Validador JSON
- [ ] Encoder/Decoder Base64
- [ ] Explicador de Expressões Cron
- [ ] Previewer de Markdown
- [ ] Horário Mundial

---

## 3. Utilitários Brasil-Específicos

_Foco em desenvolvedores e usuários brasileiros._

- [ ] Gerador e Validador de CPF/CNPJ
- [ ] Validador de CNH
- [ ] Gerador de Placas (Mercosul/Antiga)
- [ ] Consulta de CEP (integração API)

---

## 4. Matemática, Finanças e Saúde

_Cálculos rápidos para o dia a dia._

- [ ] Calculadora de Regra de Três
- [ ] Calculadora de Porcentagem (refatorar existente)
- [ ] Calculadora de Juros (Compostos e Simples)
- [ ] Calculadora de IMC
- [ ] Conversor de Moedas (integração API)
- [ ] MDC, MMC e Fatoração

---

## 5. Texto e SEO

_Ferramentas para criadores de conteúdo._

- [ ] Contador de Caracteres e Palavras
- [ ] Gerador de Texto Lorem Ipsum (refatorar existente)
- [ ] Gerador de Senhas Fortes
- [ ] Case Converter (UPPER, lower, camelCase, snake_case, kebab-case)
- [ ] Removedor de Linhas Duplicadas

---

## 6. Multimídia e Design

_Processamento de arquivos e conversão visual._

- [ ] Compressor de Imagens (refatorar existente)
- [ ] Conversor de PX para REM, CM, etc.
- [ ] Seletor de Cores (RGB/HEX/HSL)

---

## Padrão de Implementação

Cada ferramenta deve seguir:

1. **Service Class** - Lógica isolada em `app/Services/`
2. **URLs SEO** - Rotas amigáveis para indexação (ex: `/tools/uuid/v4`)
3. **Valores Pré-gerados** - Página abre com dados já gerados
4. **Vanilla JS** - Sem Alpine.js, classes ES6 buildadas pelo Vite
5. **Preline UI** - Dropdowns e componentes estilizados

---

## Prioridade de Implementação

1. Refatorar Lorem/Percentage/Image (consistência)
2. CPF/CNPJ (alta busca Brasil)
3. JSON Formatter (alta busca dev)
4. Base64 (simples e útil)
5. Slugify (rápido de implementar)
