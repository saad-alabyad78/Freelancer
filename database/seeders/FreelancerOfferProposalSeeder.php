<?php

namespace Database\Seeders;

use App\Models\FreelancerOfferProposal;
use Illuminate\Database\Seeder;

class FreelancerOfferProposalSeeder extends Seeder
{
    public function run(): void
    {
        FreelancerOfferProposal::create([
            'freelancer_id' => 1,
            'client_id' => 1,
            'freelancer_offer_id' => 1,
            'message' => 'I am interested in your e-commerce project. I have experience in building such websites.',
            'accepted_at' => null,
            'rejected_at' => null,
        ]);

        FreelancerOfferProposal::create([
            'freelancer_id' => 2,
            'client_id' => 2,
            'freelancer_offer_id' => 2,
            'message' => 'I can develop your mobile app. Let’s discuss further details.',
            'accepted_at' => now(),
            'rejected_at' => null,
        ]);

        FreelancerOfferProposal::create([
            'freelancer_id' => 3,
            'client_id' => 3,
            'freelancer_offer_id' => 3,
            'message' => 'I will optimize your website’s SEO to improve its ranking.',
            'accepted_at' => null,
            'rejected_at' => now(),
        ]);

        FreelancerOfferProposal::create([
            'freelancer_id' => 4,
            'client_id' => 4,
            'freelancer_offer_id' => 4,
            'message' => 'I can design the marketing materials you need with a modern touch.',
            'accepted_at' => null,
            'rejected_at' => null,
        ]);

        FreelancerOfferProposal::create([
            'freelancer_id' => 5,
            'client_id' => 5,
            'freelancer_offer_id' => 5,
            'message' => 'I can write high-quality content for your website.',
            'accepted_at' => null,
            'rejected_at' => null,
        ]);
    }
}
