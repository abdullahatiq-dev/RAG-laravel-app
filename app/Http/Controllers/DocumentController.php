<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentChunk;
use App\Services\ChunkingService;
use App\Services\EmbeddingService;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class DocumentController extends Controller
{
       public function __construct(
        protected ChunkingService $chunkingService,
        protected EmbeddingService $embeddingService
    ) {}

    public function store(Request $request): Document
    {
        $data = $request->all();

        // 1. Save document
        $document = Document::create([
            'title' => $data['title'] ?? null,
            'content' => $data['content'],
            'source' => $data['source'] ?? 'manual',
        ]);

        // 2. Chunk content
        $chunks = $this->chunkingService->chunk($data['content']);

        // 3. Generate embeddings + store
        foreach ($chunks as $chunk) {

            $response = $this->embeddingService->generate($chunk);

            DocumentChunk::create([
                'document_id' => $document->id,
                'content' => $chunk,
                'embedding' => $response['embedding'],
            ]);
        }

        return $document;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf'
        ]);

        // Save uploaded pdf
        $path = $request->file('file')->store('documents');

        // Extract text
        $parser = new Parser();

        $pdf = $parser->parseFile(storage_path('app/private/' . $path));

        $content = $pdf->getText();

        // Save document
        $document = Document::create([
            'title' => $request->file('file')->getClientOriginalName(),
            'content' => $content,
            'source' => 'pdf'
        ]);

        // Chunk text
        $chunks = $this->chunkingService->chunk($content);

        // Generate embeddings
        foreach ($chunks as $chunk) {

         \Log::info('Chunk', [
        'chunk' => $chunk
    ]);

            $response = $this->embeddingService->generate($chunk);

            DocumentChunk::create([
                'document_id' => $document->id,
                'content' => $chunk,
                'embedding' => json_encode($response['embedding'])
            ]);
        }

        return response()->json([
            'message' => 'PDF indexed successfully'
        ]);
    }

}
