# Tools Roadmap

Checklist de ferramentas a serem implementadas no projeto.

## Legenda

- [x] Concluído
- [ ] Pendente

---

## 1. Tools Existentes (Concluídas)

Todas as tools já usam Vanilla JS ES6. Alpine.js foi removido.

- [x] **UUID Generator** - UuidService + Vanilla JS + URLs SEO
- [x] **Lorem Ipsum** - LoremService + Vanilla JS
- [x] **Calculadora %** - Vanilla JS (100% frontend)
- [x] **Compressor de Imagem** - Vanilla JS (100% frontend)

---

## 2. Ferramentas para Desenvolvedores (Alta Prioridade)

_Essenciais para atrair tráfego técnico e recorrência._

- [x] Gerador de UUID/ULID/CUID
- [x] Slugify (Gerador de Slugs)
- [ ] Formatador e Validador JSON
- [x] Encoder/Decoder Base64
- [ ] Explicador de Expressões Cron
- [ ] Previewer de Markdown
- [ ] Horário Mundial

---

## 3. Utilitários Brasil-Específicos

_Foco em desenvolvedores e usuários brasileiros._

- [x] Gerador e Validador de CPF/CNPJ
- [ ] Validador de CNH
- [ ] Gerador de Placas (Mercosul/Antiga)
- [ ] Consulta de CEP (integração API)

---

## 4. Matemática, Finanças e Saúde

_Cálculos rápidos para o dia a dia._

- [ ] Calculadora de Regra de Três
- [x] Calculadora de Porcentagem
- [ ] Calculadora de Juros (Compostos e Simples)
- [ ] Calculadora de IMC
- [ ] Conversor de Moedas (integração API)
- [ ] MDC, MMC e Fatoração

---

## 5. Texto e SEO

_Ferramentas para criadores de conteúdo._

- [ ] Contador de Caracteres e Palavras
- [x] Gerador de Texto Lorem Ipsum
- [ ] Gerador de Senhas Fortes
- [ ] Case Converter (UPPER, lower, camelCase, snake_case, kebab-case)
- [ ] Removedor de Linhas Duplicadas

---

## 6. Multimídia e Design

_Processamento de arquivos e conversão visual._

- [x] Compressor de Imagens
- [ ] Conversor de PX para REM, CM, etc.
- [ ] Seletor de Cores (RGB/HEX/HSL)

---

## Padrão de Implementação

Cada ferramenta deve seguir:

1. **Service Class** - Lógica no backend em `app/Services/` (quando aplicável)
2. **Valores Pré-gerados** - Página abre com dados já gerados (quando fizer sentido)
3. **Vanilla JS** - Classes ES6 buildadas pelo Vite, sem Alpine.js
4. **Preline UI** - Dropdowns e componentes estilizados

---

## Prioridade de Implementação

1. ~~Refatorar Lorem/Percentage/Image~~ ✅
2. ~~CPF/CNPJ (alta busca Brasil)~~ ✅
3. JSON Formatter (alta busca dev)
4. ~~Base64 (simples e útil)~~ ✅
5. ~~Slugify (rápido de implementar)~~ ✅
