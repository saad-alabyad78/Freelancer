<?php

namespace App\Http\Controllers\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ConversationController extends Controller
{
    public function createConversation(Request $request)
    {
        $conversation = Conversation::create();
        $conversation->participants()->attach($request->user_ids);
        return response()->json($conversation);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)->get();
        return response()->json($messages);
    }

    public function getConversations(Request $request)
    {
        $conversations = $request->user()->conversations;
        return response()->json($conversations);
    }
}
