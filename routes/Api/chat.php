<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ConversationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations', [ConversationController::class, 'createConversation']);
    Route::post('/conversations/{conversationId}/messages', [ConversationController::class, 'sendMessage']);
    Route::get('/conversations/{conversationId}/messages', [ConversationController::class, 'getMessages']);
    Route::get('/conversations/{conversationId}/messages/{messageId}', [ConversationController::class, 'getMessagesByMessageId']);
    Route::get('/conversations', [ConversationController::class, 'getConversations']);
    Route::post('conversations/{conversation}/ban', [MessageController::class, 'banUser']);
Route::post('messages/{message}/like', [MessageController::class, 'likeMessage']);

});
