<?php

namespace App\Http\Controllers\Chat;

use App\Models\Message;
use App\Events\MessageSent;
use App\Models\MessageLike;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\ConversationUserBan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\CreateConversationRequest;

class ConversationController extends Controller
{
    /**
     * Create a new conversation.
     *
     * @param  \App\Http\Requests\Chat\CreateConversationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createConversation(CreateConversationRequest $request)
    {
        $conversation = Conversation::create();
        $conversation->participants()->attach($request->user_ids);
        return response()->json($conversation);
    }

    /**
     * Send a message in a conversation.
     *
     * @param  \App\Http\Requests\Chat\SendMessageRequest  $request
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(SendMessageRequest $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Check if the user is banned from the conversation
        if (ConversationUserBan::where('conversation_id', $conversationId)
            ->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are banned from this conversation'], 403);
        }

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

        Cache::put('user-is-online-' . $request->user()->id, true, now()->addMinutes(5));

        return response()->json($message);
    }

    /**
     * Get messages from a conversation.
     *
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->with('replies', 'parent')
            ->paginate(50);
        return response()->json($messages);
    }

    /**
     * Get all conversations for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversations(Request $request)
    {
        $conversations = $request->user()->conversations;
        return response()->json($conversations);
    }

    /**
     * Get messages from a specific message in a conversation.
     *
     * @param  int  $conversationId
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Ban a user from a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function banUser(Request $request, $conversationId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        if ($conversation->users()->where('user_id', auth()->id())->doesntExist()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

        $ban = ConversationUserBan::create([
            'conversation_id' => $conversationId,
            'user_id' => $request->user_id,
        ]);

        return response()->json($ban, 201);
    }

    /**
     * Like a message in a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeMessage(Request $request, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->conversation->users()->where('user_id', auth()->id())->doesntExist()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

        $like = MessageLike::create([
            'message_id' => $messageId,
            'user_id' => auth()->id(),
        ]);

        return response()->json($like, 201);
    }
     // دالة تحديث حالة الأونلاين
     public function updateOnlineStatus()
     {
         $user = auth()->user();
         Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(5));
         $user->update(['last_seen' => now()]);

         broadcast(new UserOnlineStatusUpdated($user))->toOthers();

         return response()->json(['message' => 'Status updated']);
     }

     // دالة الحصول على حالة المستخدم
     public function getUserStatus($userId)
     {
         $user = User::findOrFail($userId);
         $isOnline = Cache::has('user-is-online-' . $user->id);

         return response()->json([
             'is_online' => $isOnline,
             'last_seen' => $user->last_seen,
         ]);
     }
 }
