<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;

class UuidService
{
    public const TYPES = [
        'uuid-v1' => [
            'label' => 'UUID v1',
            'description' => 'Baseado em timestamp e MAC address. Ordenável por tempo.',
            'short' => 'Timestamp + MAC',
        ],
        'uuid-v4' => [
            'label' => 'UUID v4',
            'description' => 'Completamente aleatório. O mais comum e recomendado.',
            'short' => 'Aleatório',
        ],
        'uuid-v6' => [
            'label' => 'UUID v6',
            'description' => 'Reordenação do v1 para melhor ordenação em bancos de dados.',
            'short' => 'Ordenável',
        ],
        'uuid-v7' => [
            'label' => 'UUID v7',
            'description' => 'Baseado em Unix timestamp. Ideal para bancos de dados modernos.',
            'short' => 'Unix timestamp',
        ],
        'cuid' => [
            'label' => 'CUID',
            'description' => 'Collision-resistant ID. Compacto e seguro para URLs.',
            'short' => 'Collision-resistant',
        ],
        'nanoid' => [
            'label' => 'NanoID',
            'description' => 'ID compacto de 21 caracteres. Mais curto que UUID.',
            'short' => 'Compacto',
        ],
    ];

    public function generate(string $type, int $quantity = 5): array
    {
        $ids = [];

        for ($i = 0; $i < $quantity; $i++) {
            $ids[] = match ($type) {
                'uuid-v1' => Uuid::uuid1()->toString(),
                'uuid-v4' => Uuid::uuid4()->toString(),
                'uuid-v6' => Uuid::uuid6()->toString(),
                'uuid-v7' => Uuid::uuid7()->toString(),
                'cuid' => $this->generateCuid(),
                'nanoid' => $this->generateNanoId(),
                default => Uuid::uuid4()->toString(),
            };
        }

        return $ids;
    }

    public function getTypes(): array
    {
        return self::TYPES;
    }

    public function getTypeInfo(string $type): ?array
    {
        return self::TYPES[$type] ?? null;
    }

    public function isValidType(string $type): bool
    {
        return isset(self::TYPES[$type]);
    }

    private function generateCuid(): string
    {
        $timestamp = base_convert((string) floor(microtime(true) * 1000), 10, 36);
        $random = base_convert(bin2hex(random_bytes(8)), 16, 36);
        return 'c' . $timestamp . $random;
    }

    private function generateNanoId(int $size = 21): string
    {
        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
        $id = '';
        $bytes = random_bytes($size);
        for ($i = 0; $i < $size; $i++) {
            $id .= $alphabet[ord($bytes[$i]) & 63];
        }
        return $id;
    }
}
