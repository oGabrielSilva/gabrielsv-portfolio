# Planejamento de Tools - Suite de Utilitários Web

Este documento detalha as ferramentas utilitárias a serem implementadas, organizadas por prioridade e categoria, com foco em SEO, utilidade prática e performance.

## 1. Ferramentas para Desenvolvedores (Alta Prioridade)

_Essenciais para atrair tráfego técnico e recorrência._

- **Gerador de UUID/ULID/CUID**
- **Slugify (Gerador de Slugs)**
- **Formatador e Validador JSON**
- **Encoder/Decoder Base64**
- **Regex Tester**
- **Hash Generator**
- **JWT Generator/Validator**
- **Explicador de Expressões Cron:** Interface visual para entender agendamentos do sistema e gerar cron jobs
- **Previewer de Markdown:** Editor simples com visualização em tempo real.
- **Horário Mundial:** Uma view que deve mostrar algum local do planeta, seu horário, dia, etc.

## 2. Utilitários Brasil-Específicos

_Foco em desenvolvedores e usuários brasileiros que testam sistemas locais._

- **Gerador e Validador de CPF/CNPJ:** Algoritmo oficial de validação.
- **Validador de CNH:** Verificação de dígito verificador.
- **Gerador de Placas (Mercosul/Antiga):** Útil para simulações de sistemas veiculares.
- **Consulta de CEP:** Integração com APIs de localização para retorno de endereços.

## 3. Matemática, Finanças e Saúde

_Cálculos rápidos para o dia a dia._

- **Calculadora de Regra de Três:** Interface simples para cálculos de proporção.
- **Calculadora de Porcentagem:** Variações (quanto é X% de Y, aumento percentual, etc.).
- **Calculadora de Juros (Compostos e Simples)** Foco em planejamento financeiro básico.
- **Calculadora de IMC (Índice de Massa Corporal):** Ferramenta de utilidade geral para saúde.
- **Conversor de Moedas:** Integração com API de cotação em tempo real (BRL/USD/EUR).
- **MDC, MMC e Fatoração:** Ferramentas com foco educacional.

## 4. Texto e SEO (Otimização de Conteúdo)

_Ferramentas para criadores de conteúdo e otimização de busca._

- **Contador de Caracteres e Palavras:** Incluindo contagem para meta tags de SEO.
- **Gerador de Texto (Lorem Ipsum):** Incluir variações como "Dev Ipsum".
- **Gerador de Senhas Fortes:** Com opções de customização de segurança.
- **Case Converter:** Transformação para UPPERCASE, lowercase, camelCase, snake_case, kebab-case.
- **Removedor de Linhas Duplicadas:** Limpeza de listas de texto.

## 5. Multimídia e Design

_Processamento de arquivos e conversão visual._

- **Compressor de Imagens:** Otimização de arquivos (priorizar processamento via Client-side/JS para poupar o servidor Laravel).
- **Conversor de PX para REM, CM, etc.**
- **Seletor de Cores (RGB/HEX/HSL):** Gerador de paletas e conversão de formatos de cor.

---

## Estratégia de Implementação (Backlog)

### Itens Removidos/Ajustados

- **Dados (RPG):** Removido por ser um nicho muito específico e de baixa busca orgânica geral.
- **Calculadora de Resto da Divisão:** Incorporada na categoria de Matemática como função secundária.
- **Conversor de Números Romanos:** Baixa prioridade; implementar apenas após as ferramentas principais.

### Sugestão Técnica para Laravel

1.  **Backend:** Utilizar `Services` dedicados para cada lógica de cálculo, permitindo a criação de uma API pública para as ferramentas no futuro.
2.  **Frontend:** Utilizar componentes Blade ou Vue/Livewire para garantir que as ferramentas de validação (como CPF) funcionem instantaneamente sem recarregar a página.

Detalhe: remover uso do Alpine.js, desnecessário. Algumas ferramentas exigem abrir com valores já definidos, como a lorem ipsum. Ao ser acessada, já ter um lorem gerado, igual a de uuid, já ter uuids gerados. O tipo do uuid ser passado via url para poder aparecer no Google como gerador de uuid, gerador de cuid e abrir a página correta.
