<?php

namespace App\Services;

class LoremService
{
    private const LOREM_WORDS = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit',
        'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
        'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation',
        'ullamco', 'laboris', 'nisi', 'aliquip', 'ex', 'ea', 'commodo', 'consequat', 'duis',
        'aute', 'irure', 'in', 'reprehenderit', 'voluptate', 'velit', 'esse', 'cillum',
        'fugiat', 'nulla', 'pariatur', 'excepteur', 'sint', 'occaecat', 'cupidatat', 'non',
        'proident', 'sunt', 'culpa', 'qui', 'officia', 'deserunt', 'mollit', 'anim', 'id',
        'est', 'laborum', 'perspiciatis', 'unde', 'omnis', 'iste', 'natus', 'error',
        'voluptatem', 'accusantium', 'doloremque', 'laudantium', 'totam', 'rem', 'aperiam',
        'eaque', 'ipsa', 'quae', 'ab', 'illo', 'inventore', 'veritatis', 'quasi', 'architecto',
        'beatae', 'vitae', 'dicta', 'explicabo', 'nemo', 'ipsam', 'quia', 'voluptas',
        'aspernatur', 'aut', 'odit', 'fugit'
    ];

    public const TYPES = [
        'paragraphs' => [
            'label' => 'Parágrafos',
            'description' => 'Blocos de texto estruturados',
        ],
        'sentences' => [
            'label' => 'Sentenças',
            'description' => 'Frases completas',
        ],
        'words' => [
            'label' => 'Palavras',
            'description' => 'Palavras soltas',
        ],
    ];

    /**
     * Gera texto Lorem Ipsum
     */
    public function generate(string $type, int $quantity, bool $startWithLorem = true): array
    {
        return match ($type) {
            'words' => $this->generateWords($quantity, $startWithLorem),
            'sentences' => $this->generateSentences($quantity, $startWithLorem),
            'paragraphs' => $this->generateParagraphs($quantity, $startWithLorem),
            default => $this->generateParagraphs($quantity, $startWithLorem),
        };
    }

    /**
     * Gera palavras aleatórias
     */
    private function generateWords(int $count, bool $startWithLorem): array
    {
        $words = [];

        if ($startWithLorem) {
            $words = ['Lorem', 'ipsum'];
            $count -= 2;
        }

        for ($i = 0; $i < $count; $i++) {
            $words[] = self::LOREM_WORDS[array_rand(self::LOREM_WORDS)];
        }

        return [$this->joinWords($words)];
    }

    /**
     * Gera sentenças
     */
    private function generateSentences(int $count, bool $startWithLorem): array
    {
        $sentences = [];

        for ($i = 0; $i < $count; $i++) {
            if ($i === 0 && $startWithLorem) {
                $sentences[] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
            } else {
                $sentences[] = $this->generateSentence();
            }
        }

        return $sentences;
    }

    /**
     * Gera uma sentença aleatória
     */
    private function generateSentence(int $minWords = 8, int $maxWords = 15): string
    {
        $count = rand($minWords, $maxWords);
        $words = [];

        for ($i = 0; $i < $count; $i++) {
            $words[] = self::LOREM_WORDS[array_rand(self::LOREM_WORDS)];
        }

        $words[0] = ucfirst($words[0]);
        return implode(' ', $words) . '.';
    }

    /**
     * Gera parágrafos
     */
    private function generateParagraphs(int $count, bool $startWithLorem): array
    {
        $paragraphs = [];

        for ($i = 0; $i < $count; $i++) {
            if ($i === 0 && $startWithLorem) {
                $paragraphs[] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
                    $this->generateParagraph(3, 6);
            } else {
                $paragraphs[] = $this->generateParagraph();
            }
        }

        return $paragraphs;
    }

    /**
     * Gera um parágrafo aleatório
     */
    private function generateParagraph(int $minSentences = 4, int $maxSentences = 8): string
    {
        $count = rand($minSentences, $maxSentences);
        $sentences = [];

        for ($i = 0; $i < $count; $i++) {
            $sentences[] = $this->generateSentence();
        }

        return implode(' ', $sentences);
    }

    /**
     * Junta palavras em uma string
     */
    private function joinWords(array $words): string
    {
        return implode(' ', $words);
    }

    /**
     * Retorna informações sobre os tipos
     */
    public function getTypes(): array
    {
        return self::TYPES;
    }

    /**
     * Valida se o tipo é válido
     */
    public function isValidType(string $type): bool
    {
        return isset(self::TYPES[$type]);
    }
}
