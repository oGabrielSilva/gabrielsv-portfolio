/**
 * Hidratação de gráficos nos posts do blog.
 *
 * Carregado APENAS nas páginas que contêm gráfico (blog/show empurra este
 * entrypoint via @push('scripts') só quando $hasChart). Em nenhuma outra
 * página o Chart.js é baixado.
 *
 * O servidor não desenha o gráfico: emite só o container com os dados —
 *   <figure class="chart" data-chart='{json}' aria-label="..."></figure>
 * O aria-label leva a descrição textual (SEO/leitores de tela). Aqui lemos o
 * JSON do data-chart e o Chart.js desenha o gráfico de fato num <canvas>.
 */
import Chart from "chart.js/auto";

// Tema dark alinhado ao design system do site (app.css @theme).
const PRIMARY = "#00d1b2"; // bulma-primary
const GRID_COLOR = "rgba(255,255,255,0.05)";
const TEXT_MUTED = "#9ca3af";
const TEXT_FAINT = "#6b7280";
const SURFACE = "#1a1a1a"; // neutral-900
const BORDER = "#2f2f2f"; // neutral-700
const FONT =
    "'Google Sans Flex', ui-sans-serif, system-ui, -apple-system, sans-serif";

Chart.defaults.font.family = FONT;
Chart.defaults.font.size = 12;
Chart.defaults.color = TEXT_MUTED;

/**
 * Converte o nosso schema (data-chart) no config do Chart.js.
 * @param {object} spec
 */
function toChartConfig(spec) {
    const horizontal = Boolean(spec.horizontal);
    const stacked = Boolean(spec.stacked);

    const datasets = (spec.datasets || []).map((ds) => ({
        label: ds.label,
        data: ds.data,
        backgroundColor: ds.color,
        // Sem arredondamento e sem limitar espessura — igual ao HTML original.
        // Deixa o Chart.js distribuir as barras com os defaults (barras grossas,
        // espaçamento natural entre categorias).
        borderWidth: 0,
    }));

    const valueAxis = {
        stacked,
        beginAtZero: true,
        border: { display: false },
        grid: { color: GRID_COLOR, drawTicks: false },
        ticks: { color: TEXT_FAINT, padding: 8, font: { size: 11 } },
        title: spec.xLabel
            ? {
                  display: true,
                  text: spec.xLabel,
                  color: TEXT_MUTED,
                  font: { size: 12, weight: "500" },
                  padding: { top: 10 },
              }
            : { display: false },
    };
    const categoryAxis = {
        stacked,
        border: { display: false },
        grid: { display: false },
        ticks: { color: "#d1d5db", font: { size: 13 }, padding: 6 },
    };

    return {
        type: spec.type || "bar",
        data: { labels: spec.labels || [], datasets },
        options: {
            indexAxis: horizontal ? "y" : "x",
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 4, right: 12, bottom: 4, left: 4 } },
            animation: { duration: 600, easing: "easeOutQuart" },
            interaction: { mode: "index", intersect: false },
            // Eixo de valor é o X no horizontal, o Y no vertical.
            scales: horizontal
                ? { x: valueAxis, y: categoryAxis }
                : { x: categoryAxis, y: valueAxis },
            plugins: {
                title: {
                    display: Boolean(spec.title),
                    text: spec.title,
                    color: "#e4e4e7",
                    align: "start",
                    font: { size: 14, weight: "600" },
                    padding: { bottom: 16 },
                },
                legend: {
                    display: datasets.length > 1,
                    position: "bottom",
                    align: "start",
                    labels: {
                        color: TEXT_MUTED,
                        usePointStyle: true,
                        pointStyle: "circle",
                        boxWidth: 8,
                        boxHeight: 8,
                        padding: 16,
                        font: { size: 12 },
                    },
                },
                tooltip: {
                    backgroundColor: SURFACE,
                    borderColor: BORDER,
                    borderWidth: 1,
                    titleColor: "#fff",
                    bodyColor: "#e4e4e7",
                    padding: 12,
                    cornerRadius: 8,
                    boxPadding: 6,
                    usePointStyle: true,
                    callbacks: {
                        label: (ctx) =>
                            ` ${ctx.dataset.label}: ${ctx.formattedValue}`,
                        // No empilhado, mostra o total no rodapé do tooltip.
                        footer: (items) => {
                            if (!stacked || items.length < 2) return "";
                            const total = items.reduce(
                                (sum, i) => sum + (i.parsed[horizontal ? "x" : "y"] || 0),
                                0,
                            );
                            return `Total: ${total}`;
                        },
                    },
                    footerColor: PRIMARY,
                    footerFont: { weight: "600" },
                },
            },
        },
    };
}

function hydrate(figure) {
    const raw = figure.getAttribute("data-chart");
    if (!raw || figure.dataset.hydrated) return;

    let spec;
    try {
        spec = JSON.parse(raw);
    } catch {
        // JSON inválido: deixa o container vazio (aria-label ainda descreve).
        return;
    }

    const canvas = document.createElement("canvas");
    figure.insertBefore(canvas, figure.firstChild);
    figure.dataset.hydrated = "1";

    try {
        new Chart(canvas, toChartConfig(spec));
    } catch {
        canvas.remove();
        delete figure.dataset.hydrated;
    }
}

function init() {
    document.querySelectorAll("figure.chart[data-chart]").forEach(hydrate);
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
