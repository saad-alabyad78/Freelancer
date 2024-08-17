<?php

namespace Database\Seeders;

use App\Models\JobOfferProposal;
use Illuminate\Database\Seeder;

class JobOfferProposalSeeder extends Seeder
{
    public function run(): void
    {
        // مقترحات لفرص عمل من الفريلانسرز
        JobOfferProposal::create([
            'freelancer_id' => 1,
            'job_offer_id' => 1,
            'message' => 'I am highly interested in this job and have the skills to fulfill the requirements.',
            'accepted_at' => now(),
            'rejected_at' => null,
        ]);

        JobOfferProposal::create([
            'freelancer_id' => 2,
            'job_offer_id' => 2,
            'message' => 'I can handle this job efficiently within the given deadline.',
            'accepted_at' => null,
            'rejected_at' => now(),
        ]);

        JobOfferProposal::create([
            'freelancer_id' => 3,
            'job_offer_id' => 3,
            'message' => 'I have done similar projects and would like to work on this offer.',
            'accepted_at' => null,
            'rejected_at' => null,
        ]);

        JobOfferProposal::create([
            'freelancer_id' => 4,
            'job_offer_id' => 4,
            'message' => 'I am confident in delivering high-quality results for this project.',
            'accepted_at' => now(),
            'rejected_at' => null,
        ]);

        JobOfferProposal::create([
            'freelancer_id' => 5,
            'job_offer_id' => 5,
            'message' => 'I am eager to contribute my expertise to your project.',
            'accepted_at' => null,
            'rejected_at' => null,
        ]);
    }
}
