<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmbeddingService
{
    public function generate(string $text): array
    {
        $response = Http::post('http://127.0.0.1:8000/embed', [
            'text' => $text
        ]);

        if ($response->failed()) {
            throw new \Exception('Embedding service failed');
        }

        $embedding =  $response->json()['embedding'];
        $text = $response->json()['text'];

        return [
            'embedding' => $embedding,
            'text' => $text
        ];
    }

}