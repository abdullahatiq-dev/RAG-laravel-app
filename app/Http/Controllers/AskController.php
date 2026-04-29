<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmbeddingService;
use App\Services\RetrievalService;
use App\Services\AnswerService;

class AskController extends Controller
{
    public function ask(Request $request)
    {
        $question = $request->input('question');

        // 1. Convert question → embedding
        $queryData = app(EmbeddingService::class)->generate($question);

         $queryEmbedding = $queryData['embedding']; 
        \Log::info('Query embedding generated', ['embedding_length' => $queryEmbedding]);
        // 2. Retrieve similar chunks
        $results = app(RetrievalService::class)
            ->search($queryEmbedding);
            \Log::info('Retrieval results', ['results' => $results]);
        // 3. Generate answer
        $answer = app(AnswerService::class)
            ->generate($question, $results);
        \Log::info('Generated answer', ['answer' => $answer]);

        return response()->json([
            'question' => $question,
            'answer' => $answer,
            'matches' => $results
        ]);
    }
}