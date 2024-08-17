<?php

namespace Database\Seeders;

use App\Models\FreelancerOffer;
use Illuminate\Database\Seeder;

class FreelancerOfferSeeder extends Seeder
{
    public function run(): void
    {
        FreelancerOffer::create([
            'freelancer_id' => 1,
            'sub_category_id' => 1,
            'title' => 'Build a Custom E-commerce Website',
            'status' => 'open',
            'description' => 'I will build a fully custom e-commerce website with a user-friendly interface.',
            'min_price' => 1000,
            'max_price' => 5000,
            'days' => 30,
            'posted_at' => now(),
            'proposals_count' => 2,
        ]);

        FreelancerOffer::create([
            'freelancer_id' => 2,
            'sub_category_id' => 2,
            'title' => 'Mobile App Development (iOS and Android)',
            'status' => 'in_progress',
            'description' => 'I will develop a cross-platform mobile app for both iOS and Android.',
            'min_price' => 2000,
            'max_price' => 7000,
            'days' => 45,
            'posted_at' => now()->subDays(10),
            'proposals_count' => 3,
        ]);

        FreelancerOffer::create([
            'freelancer_id' => 3,
            'sub_category_id' => 3,
            'title' => 'SEO Optimization for Websites',
            'status' => 'completed',
            'description' => 'I will improve your website’s ranking on search engines through expert SEO techniques.',
            'min_price' => 800,
            'max_price' => 3000,
            'days' => 15,
            'posted_at' => now()->subDays(20),
            'proposals_count' => 5,
        ]);

        FreelancerOffer::create([
            'freelancer_id' => 4,
            'sub_category_id' => 4,
            'title' => 'Graphic Design for Marketing Materials',
            'status' => 'open',
            'description' => 'I will design eye-catching marketing materials, including brochures, flyers, and banners.',
            'min_price' => 300,
            'max_price' => 1500,
            'days' => 10,
            'posted_at' => now()->subDays(5),
            'proposals_count' => 1,
        ]);

        FreelancerOffer::create([
            'freelancer_id' => 5,
            'sub_category_id' => 5,
            'title' => 'Content Writing and Blogging Services',
            'status' => 'open',
            'description' => 'I offer professional content writing services, including blog posts, articles, and website content.',
            'min_price' => 100,
            'max_price' => 500,
            'days' => 7,
            'posted_at' => now()->subDays(2),
            'proposals_count' => 0,
        ]);
    }
}

