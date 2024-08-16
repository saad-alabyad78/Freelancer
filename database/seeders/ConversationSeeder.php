<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $conversation = Conversation::create();

        $conversation->users()->attach([$user1->id, $user2->id]);

    }
}
