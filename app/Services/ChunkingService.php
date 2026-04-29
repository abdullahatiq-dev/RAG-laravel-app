<?php

namespace App\Services;

class ChunkingService
{
    public function chunk(string $text, int $size = 500): array
    {
        $chunks = [];

        $length = strlen($text);

        for ($i = 0; $i < $length; $i += $size) {
            $chunks[] = substr($text, $i, $size);
        }

        return $chunks;
    }
}