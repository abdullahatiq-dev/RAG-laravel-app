<?php

use App\Http\Controllers\AskController;
use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test-embedding', function () {
    $embedding = app(\App\Services\EmbeddingService::class)
        ->generate('How many leaves do employees get?');

    return response()->json([
        'length' => count($embedding),
        'sample' => array_slice($embedding, 0, 5)
    ]);
});

Route::post('/documents', [DocumentController::class, 'store']);
Route::post('/ask', [AskController::class, 'ask']);