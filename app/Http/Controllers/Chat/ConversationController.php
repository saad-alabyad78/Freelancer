<?php

namespace App\Http\Controllers\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\CreateConversationRequest;
use App\Http\Requests\SendMessageRequest;
use App\Events\MessageSent;

class ConversationController extends Controller
{
    public function createConversation(CreateConversationRequest $request)
    {
        $conversation = Conversation::create();
        $conversation->participants()->attach($request->user_ids);
        return response()->json($conversation);
    }

    public function sendMessage(SendMessageRequest $request, $conversationId)
    {
        $validated = $request->validated();

        $messageData = [
            'conversation_id' => $conversationId,
            'user_id' => $request->user()->id,
            'message' => $validated['message'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $messageData['image'] = $request->file('image')->store('images', 'public');
        }

        $message = Message::create($messageData);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->with('replies', 'parent')
            ->paginate(50);
        return response()->json($messages);
    }

    public function getConversations(Request $request)
    {
        $conversations = $request->user()->conversations;
        return response()->json($conversations);
    }

    public function getMessagesByMessageId($conversationId, $messageId)
    {
        $message = Message::where('conversation_id', $conversationId)
            ->where('id', $messageId)
            ->firstOrFail();

        $messagePosition = Message::where('conversation_id', $conversationId)
            ->where('id', '<=', $messageId)
            ->count();

        $page = ceil($messagePosition / 50);

        $messages = Message::where('conversation_id', $conversationId)
            ->with('replies', 'parent')
            ->orderBy('created_at', 'asc')
            ->paginate(50, ['*'], 'page', $page);

        return response()->json($messages);
    }
}
