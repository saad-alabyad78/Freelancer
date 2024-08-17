<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillableSeeder extends Seeder
{
    public function run()
    {
        DB::table('skillables')->insert([
            [
                'skill_id' => 1,
                'skillable_id' => 1,
                'skillable_type' => 'App\Models\Project',
                'required' => true,
            ],
            [
                'skill_id' => 2,
                'skillable_id' => 2,
                'skillable_type' => 'App\Models\Product',
                'required' => false,
            ],
        ]);
    }
}

