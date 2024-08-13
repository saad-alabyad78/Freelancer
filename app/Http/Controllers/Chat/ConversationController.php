<?php

namespace App\Http\Controllers\Chat;

use App\Models\Conversation;
use App\Models\Like;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
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
 * APIs for managing chat conversations and messages
 */
class ConversationController extends Controller
{
    /**
     * Create a new conversation.
     *
     * @bodyParam user_id int required The ID of the user to start a conversation with. Example: 2
     * @response 201 {
     *     "message": "Conversation created successfully",
     *     "conversation_id": 1,
     *     "participants": [...],
     *     "other_user": {
     *         "id": 2,
     *         "name": "John Doe",
     *         "avatar": "avatar_url",
     *         "is_online": true,
     *         "last_seen": "2024-08-11 12:34:56"
     *     }
     * }
     * @response 200 {
     *     "message": "Conversation already exists",
     *     "conversation_id": 1,
     *     "participants": [...],
     *     "other_user": {
     *         "id": 2,
     *         "name": "John Doe",
     *         "avatar": "avatar_url",
     *         "is_online": true,
     *         "last_seen": "2024-08-11 12:34:56"
     *     }
     * }
     * @response 400 {
     *     "message": "Cannot create a conversation with yourself"
     * }
     * @response 404 {
     *     "message": "User not found"
     * }
     */
    public function createConversation(CreateConversationRequest $request)
    {
        $authUser = $request->user();
        $otherUserId = $request->user_id;

        if ($authUser->id == $otherUserId) {
            return response()->json(['message' => 'Cannot create a conversation with yourself'], 400);
        }

        $otherUser = User::find($otherUserId);
        if (!$otherUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $existingConversation = Conversation::whereHas('participants', function ($query) use ($authUser, $otherUserId) {
            $query->where('user_id', $authUser->id)->orWhere('user_id', $otherUserId);
        })->withCount('participants')->having('participants_count', '=', 2)->first();

        if ($existingConversation) {
            $participants = $existingConversation->participants()->get();
            $otherUserData = [
                'id' => $otherUser->id,
                'name' => $otherUser->first_name . ' ' . $otherUser->last_name,
                'avatar' => $otherUser->avatar,
                'is_online' => $otherUser->isOnline(),
                'last_seen' => $otherUser->last_seen,
            ];

            return response()->json([
                'message' => 'Conversation already exists',
                'conversation_id' => $existingConversation->id,
                'participants' => $participants,
                'other_user' => $otherUserData
            ], 200);
        }

        $conversation = Conversation::create();
        $conversation->participants()->attach([$authUser->id, $otherUserId]);

        $participants = $conversation->participants()->get();
        $otherUserData = [
            'id' => $otherUser->id,
            'name' => $otherUser->first_name . ' ' . $otherUser->last_name,
            'avatar' => $otherUser->avatar,
            'is_online' => $otherUser->isOnline(),
            'last_seen' => $otherUser->last_seen,
        ];

        return response()->json([
            'message' => 'Conversation created successfully',
            'conversation_id' => $conversation->id,
            'participants' => $participants,
            'other_user' => $otherUserData
        ], 201);
    }


    /**
     * Send a message in a conversation.
     *
     * @urlParam conversationId int required The ID of the conversation. Example: 1
     * @bodyParam message string The content of the message. Example: Hello
     * @bodyParam parent_id int The ID of the parent message if replying to a specific message. Example: 1
     * @bodyParam image file The image file to be sent as a message.
     * @response 200 {
     *     "id": 1,
     *     "conversation_id": 1,
     *     "user_id": 1,
     *     "message": "Hello",
     *     "parent_id": null,
     *     "image": null,
     *     "created_at": "2021-01-01T00:00:00.000000Z",
     *     "updated_at": "2021-01-01T00:00:00.000000Z"
     * }
     * @response 403 {
     *     "message": "You are banned from this conversation"
     * }
     * @response 403 {
     *     "message": "You are not a participant in this conversation"
     * }
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
     * @urlParam conversationId int required The ID of the conversation. Example: 1
     * @response 200 {
     *     "data": [
     *         {
     *             "id": 1,
     *             "conversation_id": 1,
     *             "user_id": 1,
     *             "message": "Hello",
     *             "parent_id": null,
     *             "image": null,
     *             "created_at": "2021-01-01T00:00:00.000000Z",
     *             "updated_at": "2021-01-01T00:00:00.000000Z"
     *         },
     *         // المزيد من الرسائل
     *     ]
     * }
     * @response 403 {
     *     "message": "You are not a participant in this conversation"
     * }
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
     * @response 200 {
     *     "data": [
     *         {
     *             "id": 1,
     *             "created_at": "2021-01-01T00:00:00.000000Z",
     *             "updated_at": "2021-01-01T00:00:00.000000Z"
     *         },
     *         // المزيد من المحادثات
     *     ]
     * }
     */
    public function getConversations(Request $request)
    {
        $conversations = $request->user()->conversations;
        return response()->json($conversations);
    }

    /**
     * Get messages from a specific message in a conversation.
     *
     * @urlParam conversationId int required The ID of the conversation. Example: 1
     * @urlParam messageId int required The ID of the message. Example: 1
     * @response 200 {
     *     "data": [
     *         {
     *             "id": 1,
     *             "conversation_id": 1,
     *             "user_id": 1,
     *             "message": "Hello",
     *             "parent_id": null,
     *             "image": null,
     *             "created_at": "2021-01-01T00:00:00.000000Z",
     *             "updated_at": "2021-01-01T00:00:00.000000Z"
     *         },
     *         // المزيد من الرسائل
     *     ]
     * }
     * @response 403 {
     *     "message": "You are not a participant in this conversation"
     * }
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
     * @urlParam conversationId int required The ID of the conversation. Example: 1
     * @urlParam userId int required The ID of the user to be banned. Example: 2
     * @response 200 {
     *     "message": "User banned successfully",
     *     "ban": { ... }
     * }
     * @response 404 {
     *     "message": "User not found"
     * }
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
     * @urlParam conversationId int required The ID of the conversation. Example: 1
     * @urlParam userId int required The ID of the user to be unbanned. Example: 2
     * @response 200 {
     *     "message": "User unbanned successfully"
     * }
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
     * @urlParam messageId int required The ID of the message to be liked. Example: 1
     * @response 200 {
     *     "message": "Message liked successfully",
     *     "like": { ... }
     * }
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
     * @urlParam userId int required The ID of the user. Example: 2
     * @response 200 {
     *     "online": true,
     *     "last_seen": "2021-01-01T00:00:00.000000Z"
     * }
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
