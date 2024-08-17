<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConversationUserBan;
use App\Models\Conversation;
use App\Models\User;

class ConversationUserBanSeeder extends Seeder
{
    public function run()
    {
        $conversation = Conversation::first();

        if ($conversation) {
            $user = $conversation->users()->first();

            if ($user) {
                ConversationUserBan::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
