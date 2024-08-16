<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\View;

class ViewSeeder extends Seeder
{
    public function run()
    {
        View::create([
            'user_id' => 1,
            'viewable_id' => 1,
            'viewable_type' => 'App\Models\Portfolio',
        ]);

        View::create([
            'user_id' => 2,
            'viewable_id' => 2,
            'viewable_type' => 'App\Models\Product',
        ]);
    }
}
