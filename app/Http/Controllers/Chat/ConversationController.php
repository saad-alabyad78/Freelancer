<?php

namespace App\Http\Controllers\Chat;

use App\Models\Like;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\ConversationUserBan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Events\UserOnlineStatusUpdated;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\CreateConversationRequest;

/**
 * @group Chat Management
 * 
 */
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
        $authUser = $request->user();

        $otherUserId = $request->user_id;

        if (!User::find($otherUserId)) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $conversation = Conversation::create();
        $conversation->participants()->attach([$authUser->id, $otherUserId]);

        $participants = $conversation->participants()->get();

        return response()->json([
            'message' => 'Conversation created successfully',
            'conversation_id' => $conversation->id,
            'participants' => $participants
        ], 201);
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

        if (!$conversation->participants()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
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

        return response()->json($message);
    }

    /**
     * Get messages from a conversation.
     *
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->participants()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

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
    public function getMessagesByMessageId(Request $request, $conversationId, $messageId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->participants()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

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
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function banUser(Request $request, $conversationId, $userId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->participants()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'User is not a participant in this conversation'], 403);
        }

        if (!$conversation->participants()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

        if ($request->user()->id == $userId) {
            return response()->json(['message' => 'You cannot ban yourself from the conversation'], 403);
        }

        $ban = ConversationUserBan::firstOrCreate([
            'conversation_id' => $conversationId,
            'user_id' => $userId,
        ]);

        return response()->json(['message' => 'User banned successfully', 'ban' => $ban]);
    }

    /**
     * Unban a user from a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $conversationId
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbanUser(Request $request, $conversationId, $userId)
    {
        $authUser = $request->user();

        if (!$authUser->conversations()->where('conversation_id', $conversationId)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

        $conversation = Conversation::findOrFail($conversationId);
        if (!$conversation->participants()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'User is not a participant in this conversation'], 404);
        }

        $ban = ConversationUserBan::where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->first();

        if ($ban) {
            $ban->delete();
            return response()->json(['message' => 'User unbanned successfully']);
        } else {
            return response()->json(['message' => 'User is not banned in this conversation'], 404);
        }
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
        $conversation = $message->conversation;

        if (!$conversation->participants()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You are not a participant in this conversation'], 403);
        }

        $like = Like::firstOrCreate([
            'user_id' => $request->user()->id,
            'likable_id' => $messageId,
            'likable_type' => Message::class,
        ]);

        return response()->json(['message' => 'Message liked successfully', 'like' => $like]);
    }
    /**
     * Get the online status and last seen time of a user by their ID.
     *
     * This method retrieves the 'online' status and 'last_seen' timestamp of the specified user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json([
            'is_online' => $user->isOnline(),
            'last_seen' => $user->last_seen,
        ]);
    }
}
