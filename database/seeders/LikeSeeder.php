<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    public function run(): void
    {
        Like::create([
            'user_id' => 1,
            'likable_id' => 1,
            'likable_type' => 'App\Models\Project',
        ]);

        Like::create([
            'user_id' => 2,
            'likable_id' => 2,
            'likable_type' => 'App\Models\FreelancerOffer',
        ]);

        Like::create([
            'user_id' => 3,
            'likable_id' => 3,
            'likable_type' => 'App\Models\Portfolio',
        ]);

        Like::create([
            'user_id' => 4,
            'likable_id' => 4,
            'likable_type' => 'App\Models\JobOffer',
        ]);

        Like::create([
            'user_id' => 5,
            'likable_id' => 5,
            'likable_type' => 'App\Models\FreelancerOfferProposal',
        ]);
    }
}
