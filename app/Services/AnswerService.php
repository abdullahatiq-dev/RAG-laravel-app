<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AnswerService
{
    public function generate(string $question, array $matches): string
    {
        // 1. Filter weak matches (important)
        $filtered = collect($matches)
            ->where('score', '>', 0.1)
            ->take(5)
            ->values();

        if ($filtered->isEmpty()) {
            return "No relevant information found.";
        }

        // 2. Build context
        $context = $filtered->map(function ($item, $index) {
            return ($index + 1) . ". " . $item['content'];
        })->implode("\n");

        // 3. Build prompt (VERY IMPORTANT)
        $prompt = "
            You are a helpful assistant. Answer ONLY using the provided context.
            If the answer is not in the context, then you need to answer it by yourself. Do not say you don't know if you can infer the answer from the context.

            Context:
            $context

            Question:
            $question

            Answer:
        ";

        // 4. Call Groq API
        $response = Http::withoutVerifying()->withToken(config('services.groq.api_key'))
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You answer strictly based on provided context.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 300,
            ]);

        if ($response->failed()) {
            \Log::error('Groq API failed', ['response' => $response->body()]);
            return "Error generating answer.";
        }

        return $response['choices'][0]['message']['content'] ?? 'No answer generated.';
    }
}