<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RetrievalService
{
    public function search(array $queryEmbedding, int $top = 3): array
    {
        $chunks = DB::table('document_chunks')->get();

        $results = [];

        foreach ($chunks as $chunk) {
            if (empty($chunk->embedding)) {
                continue; // skip invalid rows
            }

            $embedding = json_decode($chunk->embedding, true);

            if (is_string($embedding)) {
                $embedding = json_decode($embedding, true);
            }

            $score = $this->cosineSimilarity($queryEmbedding, $embedding);

            \Log::info('Similarity check', [
                'content' => $chunk->content,
                'score' => $score
            ]);

            $results[] = [

                'content' => $chunk->content,
                'score' => $score
            ];
        }

        // Sort by highest score
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, $top);
    }

    private function cosineSimilarity($a, $b)
    {
        $dot = $normA = $normB = 0;

        foreach ($a as $i => $val) {
            $dot += $val * $b[$i];
            $normA += $val * $val;
            $normB += $b[$i] * $b[$i];
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}