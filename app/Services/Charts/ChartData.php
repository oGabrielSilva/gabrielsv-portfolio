<?php

namespace App\Services\Charts;

use InvalidArgumentException;

/**
 * DTO imutável de um gráfico embutido num post.
 *
 * É o schema próprio do projeto (não o config cru do Chart.js). Lê de um JSON
 * guardado num <div data-chart='...'> e serve a dois consumidores: o aria-label
 * com a descrição textual (SEO/leitores de tela, já que o servidor não desenha
 * imagem) e o Chart.js, que hidrata o gráfico no cliente.
 *
 * Marcador esperado no body_html:
 *
 *   <div data-chart='{"type":"bar","stacked":true,"horizontal":true,
 *     "labels":["Node","PHP","Java"],
 *     "datasets":[{"label":"Nativo","data":[67,22,41],"color":"#1D9E75"}],
 *     "xLabel":"Linhas","title":"Verbosidade"}'></div>
 */
class ChartData
{
    /** Tipos suportados hoje. Estende aqui ao adicionar line/pie. */
    private const SUPPORTED_TYPES = ['bar'];

    /** Paleta de fallback quando um dataset não traz "color". */
    private const DEFAULT_COLORS = ['#00d1b2', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];

    /**
     * @param  list<string>  $labels
     * @param  list<array{label: string, data: list<float>, color: string}>  $datasets
     */
    public function __construct(
        public readonly string $type,
        public readonly bool $stacked,
        public readonly bool $horizontal,
        public readonly array $labels,
        public readonly array $datasets,
        public readonly ?string $xLabel,
        public readonly ?string $title,
    ) {}

    /**
     * Decodifica e valida o JSON do data-chart. Lança InvalidArgumentException
     * em qualquer inconsistência — quem chama (MarkupRenderer) captura e remove
     * o marcador em vez de quebrar a página.
     */
    public static function fromJson(string $json): self
    {
        $raw = json_decode($json, true);

        if (! is_array($raw)) {
            throw new InvalidArgumentException('data-chart não é um objeto JSON válido.');
        }

        $type = strtolower((string) ($raw['type'] ?? 'bar'));
        if (! in_array($type, self::SUPPORTED_TYPES, true)) {
            throw new InvalidArgumentException("Tipo de gráfico não suportado: {$type}.");
        }

        $labels = array_values(array_map(
            static fn ($l) => (string) $l,
            is_array($raw['labels'] ?? null) ? $raw['labels'] : [],
        ));
        if ($labels === []) {
            throw new InvalidArgumentException('data-chart sem labels.');
        }

        $rawDatasets = is_array($raw['datasets'] ?? null) ? $raw['datasets'] : [];
        if ($rawDatasets === []) {
            throw new InvalidArgumentException('data-chart sem datasets.');
        }

        $labelCount = count($labels);
        $datasets = [];
        foreach (array_values($rawDatasets) as $i => $ds) {
            if (! is_array($ds) || ! is_array($ds['data'] ?? null)) {
                throw new InvalidArgumentException("Dataset #{$i} sem array 'data'.");
            }

            // Normaliza pro mesmo comprimento dos labels (preenche com 0).
            $values = array_map(static fn ($v) => (float) $v, array_values($ds['data']));
            $values = array_slice($values, 0, $labelCount);
            $values = array_pad($values, $labelCount, 0.0);

            $datasets[] = [
                'label' => (string) ($ds['label'] ?? 'Série '.($i + 1)),
                'data' => $values,
                'color' => self::normalizeColor($ds['color'] ?? null, $i),
            ];
        }

        return new self(
            type: $type,
            stacked: (bool) ($raw['stacked'] ?? false),
            horizontal: (bool) ($raw['horizontal'] ?? false),
            labels: $labels,
            datasets: $datasets,
            xLabel: self::nullableString($raw['xLabel'] ?? null),
            title: self::nullableString($raw['title'] ?? null),
        );
    }

    /**
     * Descrição textual do gráfico pro aria-label (acessibilidade/SEO).
     * Como o servidor não desenha imagem, é por aqui que bots e leitores de
     * tela acessam os dados.
     */
    public function ariaLabel(): string
    {
        $parts = [];
        if ($this->title !== null) {
            $parts[] = $this->title.'.';
        }
        $parts[] = 'Gráfico de barras.';

        foreach ($this->labels as $i => $label) {
            $segments = [];
            foreach ($this->datasets as $ds) {
                $segments[] = $ds['label'].' '.$this->num($ds['data'][$i] ?? 0.0);
            }
            $parts[] = $label.': '.implode(', ', $segments).'.';
        }

        return implode(' ', $parts);
    }

    /** Formata número sem casas decimais desnecessárias. */
    private function num(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    /** Aceita só hex (#rgb / #rrggbb); senão cai na paleta padrão por índice. */
    private static function normalizeColor(mixed $color, int $index): string
    {
        if (is_string($color) && preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color)) {
            return $color;
        }

        return self::DEFAULT_COLORS[$index % count(self::DEFAULT_COLORS)];
    }

    private static function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
