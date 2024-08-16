<?php

namespace Database\Seeders;

use App\Models\ClientOffer;
use App\Models\Client;
use App\Models\Freelancer;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class ClientOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::first();
        $freelancer = Freelancer::first();
        $subCategory = SubCategory::first();

        ClientOffer::create([
            'client_id' => $client->id,
            'freelancer_id' => $freelancer->id,
            'sub_category_id' => $subCategory->id,
            'title' => 'Web Design Project',
            'status' => 'open',
            'description' => 'Looking for a skilled web designer to create a modern website.',
            'min_price' => 1000,
            'max_price' => 5000,
            'days' => 30,
            'posted_at' => now(),
            'proposals_count' => 5,
        ]);

        ClientOffer::create([
            'client_id' => $client->id,
            'freelancer_id' => $freelancer->id,
            'sub_category_id' => $subCategory->id,
            'title' => 'Mobile App Development',
            'status' => 'closed',
            'description' => 'Develop a mobile app for e-commerce with multiple features.',
            'min_price' => 2000,
            'max_price' => 8000,
            'days' => 60,
            'posted_at' => now(),
            'proposals_count' => 10,
        ]);

    }
}
