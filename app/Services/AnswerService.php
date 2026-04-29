<?php

namespace App\Services;

class AnswerService
{
    public function generate(string $question, array $results): string
    {
        if (empty($results)) {
            return "No relevant information found.";
        }

        // Simple approach: return best match
        $best = $results[0]['content'];

        return "Based on documents: " . $best;
    }
}