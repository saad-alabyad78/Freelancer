<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateUserOnlineStatus
{
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $isOnline = Cache::has('user-is-online-' . $user->id);

            if (!$isOnline) {
                $user->update(['last_seen' => now()]);
            }
        }

        Log::info('User online status updated successfully.');
    }
}

