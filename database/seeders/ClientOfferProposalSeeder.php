<?php

namespace Database\Seeders;

use App\Models\ClientOfferProposal;
use App\Models\Client;
use App\Models\Freelancer;
use App\Models\ClientOffer;
use Illuminate\Database\Seeder;

class ClientOfferProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::first();
        $freelancer = Freelancer::first();
        $clientOffer = ClientOffer::first();

        ClientOfferProposal::create([
            'freelancer_id' => $freelancer->id,
            'client_id' => $client->id,
            'client_offer_id' => $clientOffer->id,
            'message' => 'I can complete this project within 14 days with top quality.',
            'days' => 14,
            'price' => 1500,
            'accepted_at' => null,
            'rejected_at' => null,
        ]);
    }
}
