<?php

namespace Database\Seeders;

use App\Models\Invitation;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    public function run(): void
    {
        // دعوات من شركات لفريلانسرز مختلفة
        Invitation::create([
            'company_id' => 1,
            'freelancer_id' => 2,
            'accepted_at' => now(),
            'rejected_at' => null,
        ]);

        Invitation::create([
            'company_id' => 2,
            'freelancer_id' => 3,
            'accepted_at' => null,
            'rejected_at' => now(),
        ]);

        Invitation::create([
            'company_id' => 3,
            'freelancer_id' => 4,
            'accepted_at' => now(),
            'rejected_at' => null,
        ]);

        Invitation::create([
            'company_id' => 4,
            'freelancer_id' => 5,
            'accepted_at' => null,
            'rejected_at' => now(),
        ]);

        Invitation::create([
            'company_id' => 5,
            'freelancer_id' => 1,
            'accepted_at' => null,
            'rejected_at' => null,
        ]);
    }
}

