<?php

namespace App\Services;

class ChunkingService
{
    public function chunk(
        string $text,
        int $chunkSize = 500,
        int $overlap = 100
    ): array {

        $chunks = [];

        $start = 0;
        $length = strlen($text);

        while ($start < $length) {

            $chunks[] = substr($text, $start, $chunkSize);

            $start += ($chunkSize - $overlap);
        }

        return $chunks;
    }
}