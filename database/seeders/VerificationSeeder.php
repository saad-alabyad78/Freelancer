<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Verification;

class VerificationSeeder extends Seeder
{
    public function run()
    {
        Verification::create([
            'company_id' => 1,
            'accepted_at' => '2024-07-01',
            'rejected_at' => null,
            'response' => 'Verification accepted.',
        ]);

        Verification::create([
            'company_id' => 2,
            'accepted_at' => null,
            'rejected_at' => '2024-08-10',
            'response' => 'Verification rejected.',
        ]);
    }
}
