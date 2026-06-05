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

        \Log::info('Embedding service response', ['status' => $response->status(), 'body' => $response->body()]);

        if ($response->failed()) {
            \Log::error('Embedding service failed', ['status' => $response->status(), 'body' => $response->body()]);
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