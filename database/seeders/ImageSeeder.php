<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Image::create([
            'url' => 'https://example.com/images/image_1.png',
            'public_id' => 'image_public_id_001',
            'imagable_id' => 1,
            'imagable_type' => 'App\Models\Project',
            'deleted' => false,
        ]);

        Image::create([
            'url' => 'https://example.com/images/image_2.jpg',
            'public_id' => 'image_public_id_002',
            'imagable_id' => 2,
            'imagable_type' => 'App\Models\Portfolio',
            'deleted' => false,
        ]);

        Image::create([
            'url' => 'https://example.com/images/image_3.jpeg',
            'public_id' => 'image_public_id_003',
            'imagable_id' => 3,
            'imagable_type' => 'App\Models\Campaign',
            'deleted' => false,
        ]);

        Image::create([
            'url' => 'https://example.com/images/image_4.svg',
            'public_id' => 'image_public_id_004',
            'imagable_id' => 4,
            'imagable_type' => 'App\Models\Project',
            'deleted' => true,
        ]);

        Image::create([
            'url' => 'https://example.com/images/image_5.gif',
            'public_id' => 'image_public_id_005',
            'imagable_id' => 5,
            'imagable_type' => 'App\Models\Portfolio',
            'deleted' => false,
        ]);
    }
}
