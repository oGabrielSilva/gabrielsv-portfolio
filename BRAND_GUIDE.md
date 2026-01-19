# Gabriel Silva — Brand Guide

---

## Paleta de Cores

### Dark Mode (Default)

| Token            | Hex       | Uso                           |
| ---------------- | --------- | ----------------------------- |
| `primary`        | `#00d1b2` | CTAs, destaques, links ativos |
| `background`     | `#1a1a1a` | Fundo principal               |
| `surface`        | `#242424` | Cards, containers             |
| `border`         | `#2f2f2f` | Bordas, separadores           |
| `text-primary`   | `#ffffff` | Títulos, texto principal      |
| `text-secondary` | `#a3a3a3` | Descrições, texto auxiliar    |
| `text-muted`     | `#6b7280` | Labels, metadados             |

### Light Mode

| Token            | Hex       | Uso                           |
| ---------------- | --------- | ----------------------------- |
| `primary`        | `#00a896` | CTAs, destaques, links ativos |
| `background`     | `#ffffff` | Fundo principal               |
| `surface`        | `#f5f5f5` | Cards, containers             |
| `border`         | `#e5e5e5` | Bordas, separadores           |
| `text-primary`   | `#1a1a1a` | Títulos, texto principal      |
| `text-secondary` | `#525252` | Descrições, texto auxiliar    |
| `text-muted`     | `#a3a3a3` | Labels, metadados             |

### Cores de Acento

| Token            | Hex       |
| ---------------- | --------- |
| `accent-blue`    | `#3b82f6` |
| `accent-sky`     | `#38bdf8` |
| `accent-orange`  | `#fb923c` |
| `accent-emerald` | `#34d399` |

---

## Tipografia

- **Fonte:** Google Sans Flex
- **Fallback:** Inter, Instrument Sans, system-ui, sans-serif
- [Google Sans Flex](https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap)

| Elemento | Tamanho | Peso | Tracking | Line-height |
| -------- | ------- | ---- | -------- | ----------- |
| H1       | 48-60px | 700  | -0.025em | 1.1         |
| H2       | 30-36px | 700  | 0        | 1.2         |
| H3       | 18-20px | 700  | 0        | 1.3         |
| Body     | 14-16px | 400  | 0        | 1.6         |
| Small    | 12px    | 500  | 0.05em   | 1.4         |

---

## Espaçamento

Base: **4px**

| Token | Valor |
| ----- | ----- |
| `xs`  | 4px   |
| `sm`  | 8px   |
| `md`  | 16px  |
| `lg`  | 24px  |
| `xl`  | 32px  |
| `2xl` | 48px  |
| `3xl` | 64px  |
| `4xl` | 96px  |

---

## Border Radius

| Token  | Valor  |
| ------ | ------ |
| `sm`   | 4px    |
| `md`   | 8px    |
| `lg`   | 12px   |
| `full` | 9999px |

---

## Sombras

### Dark Mode

| Token             | Valor                                       |
| ----------------- | ------------------------------------------- |
| `shadow-card`     | `0 10px 40px -10px rgba(0, 209, 178, 0.15)` |
| `shadow-elevated` | `0 4px 20px rgba(0, 0, 0, 0.4)`             |

### Light Mode

| Token             | Valor                            |
| ----------------- | -------------------------------- |
| `shadow-card`     | `0 4px 20px rgba(0, 0, 0, 0.08)` |
| `shadow-elevated` | `0 8px 30px rgba(0, 0, 0, 0.12)` |

---

## Transições

| Token      | Valor                                |
| ---------- | ------------------------------------ |
| `fast`     | 150ms ease-out                       |
| `normal`   | 300ms ease-out                       |
| `slow`     | 500ms ease-out                       |
| `entrance` | 800ms cubic-bezier(0.33, 1, 0.68, 1) |

---

## Estados de Interação

| Estado         | Efeito                                       |
| -------------- | -------------------------------------------- |
| Hover (cards)  | translateY(-8px), border muda para `primary` |
| Hover (botões) | translateY(-4px), opacity 90%                |
| Hover (ícones) | scale(1.1) rotate(3deg)                      |
| Focus          | ring 2px `primary`, offset 2px               |
| Disabled       | opacity 50%                                  |

---

## Componentes Base

### Botão Primário

| Propriedade | Dark         | Light     |
| ----------- | ------------ | --------- |
| Background  | `primary`    | `primary` |
| Texto       | `background` | `#ffffff` |
| Padding     | 12px 24px    | 12px 24px |
| Radius      | `sm`         | `sm`      |
| Peso        | 700          | 700       |

### Botão Secundário

| Propriedade | Dark           | Light          |
| ----------- | -------------- | -------------- |
| Background  | `surface`      | `surface`      |
| Border      | 1px `border`   | 1px `border`   |
| Texto       | `text-primary` | `text-primary` |
| Padding     | 12px 24px      | 12px 24px      |
| Radius      | `sm`           | `sm`           |

### Card

| Propriedade | Dark                  | Light        |
| ----------- | --------------------- | ------------ |
| Background  | `surface` 50% opacity | `surface`    |
| Border      | 1px `border`          | 1px `border` |
| Padding     | 24-32px               | 24-32px      |
| Radius      | `lg`                  | `lg`         |

### Input

| Propriedade | Dark           | Light          |
| ----------- | -------------- | -------------- |
| Background  | `border`       | `background`   |
| Border      | 1px `#404040`  | 1px `border`   |
| Texto       | `text-primary` | `text-primary` |
| Placeholder | `text-muted`   | `text-muted`   |
| Padding     | 12px 16px      | 12px 16px      |
| Radius      | `md`           | `md`           |

---

## Referência Rápida

```
CORES (Dark)
primary:        #00d1b2
background:     #1a1a1a
surface:        #242424
border:         #2f2f2f
text-primary:   #ffffff
text-secondary: #a3a3a3

CORES (Light)
primary:        #00a896
background:     #ffffff
surface:        #f5f5f5
border:         #e5e5e5
text-primary:   #1a1a1a
text-secondary: #525252

TIPOGRAFIA
font:           Google Sans Flex
h1:             48-60px / 700
h2:             30-36px / 700
h3:             18-20px / 700
body:           14-16px / 400

ESPAÇAMENTO
base:           4px
card-padding:   24-32px
section-gap:    64-96px

RADIUS
buttons:        4px
inputs:         8px
cards:          12px

TRANSIÇÕES
hover:          300ms ease-out
entrance:       800ms ease-out-cubic
```

---

_Gabriel Silva Brand Guide — Janeiro 2026_
