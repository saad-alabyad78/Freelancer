<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ConversationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/conversations', [ConversationController::class, 'createConversation']);
    Route::post('/conversations/{conversationId}/messages', [ConversationController::class, 'sendMessage']);
    Route::get('/conversations/{conversationId}/messages', [ConversationController::class, 'getMessages']);
    Route::get('/conversations/{conversationId}/messages/{messageId}', [ConversationController::class, 'getMessagesByMessageId']);
    Route::get('/conversations', [ConversationController::class, 'getConversations']);
    Route::post('messages/{message}/like', [ConversationController::class, 'likeMessage']);
    Route::post('/conversations/{conversationId}/ban/{userId}', [ConversationController::class, 'banUser']);
    Route::post('conversations/{conversationId}/unban/{userId}', [ConversationController::class, 'unbanUser']);
    Route::get('/user/{userId}/status', [ConversationController::class, 'getUserStatus']);

});
