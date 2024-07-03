<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ConversationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations', [ConversationController::class, 'createConversation']);
    Route::post('/conversations/{conversationId}/messages', [ConversationController::class, 'sendMessage']);
    Route::get('/conversations/{conversationId}/messages', [ConversationController::class, 'getMessages']);
    Route::get('/conversations', [ConversationController::class, 'getConversations']);
});
