<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portfolio;

class PortfolioSeeder extends Seeder
{
    public function run()
    {
        Portfolio::create([
            'freelancer_id' => 1,
            'title' => 'Portfolio 1',
            'url' => 'http://example.com/portfolio1',
            'section' => 'Design',
            'description' => 'This is the first portfolio item.',
            'date' => '2024-01-01',
            'views_count' => 150,
            'likes_count' => 25,
        ]);

        Portfolio::create([
            'freelancer_id' => 2,
            'title' => 'Portfolio 2',
            'url' => 'http://example.com/portfolio2',
            'section' => 'Development',
            'description' => 'This is the second portfolio item.',
            'date' => '2024-02-15',
            'views_count' => 200,
            'likes_count' => 40,
        ]);
    }
}
