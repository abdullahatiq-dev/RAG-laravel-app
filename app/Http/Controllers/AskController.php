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
        try{

            $question = $request->input('question');
            
            // 1. Embedding
            $queryData = app(EmbeddingService::class)->generate($question);
            $queryEmbedding = $queryData['embedding'];

        \Log::info('Query embedding generated', ['embedding_length' => $queryEmbedding]);

        // 2. Retrieval
        $results = app(RetrievalService::class)->search($queryEmbedding);

        \Log::info('Retrieval results', ['results' => $results]);
        
        // 3. LLM Answer (Groq)
        $answer = app(AnswerService::class)->generate($question, $results);
        
        \Log::info('Generated answer', ['answer' => $answer]);
        \Log::info('Groq Key:', [config('services.groq.api_key')]);
        
        return response()->json([
            'question' => $question,
            'answer' => $answer,
            'matches' => $results
            ]);
    } catch (\Exception $e) {
        \Log::error('Error in AskController@ask', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred while processing your request.'], 500);
    }
}
}