<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversation = Conversation::first();

        if ($conversation) {
            $user = $conversation->users()->first();

            if ($user) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'message' => 'Hello, how can I help you with your project?',
                    'image' => null,
                    'parent_id' => null,
                ]);

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'message' => null,
                    'image' => 'https://example.com/images/message_image_1.png',
                    'parent_id' => null,
                ]);

                $previousMessage = Message::where('conversation_id', $conversation->id)->first();

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'message' => 'Sure, I can do that for you.',
                    'image' => null,
                    'parent_id' => $previousMessage ? $previousMessage->id : null,
                ]);

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'message' => 'Please find the attached design mockup.',
                    'image' => 'https://example.com/images/message_image_2.jpg',
                    'parent_id' => null,
                ]);

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'message' => 'I have made the changes you requested.',
                    'image' => null,
                    'parent_id' => $previousMessage ? $previousMessage->id : null,
                ]);
            }
        }
    }
}
