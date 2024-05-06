<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Services\xmlService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlService = new xmlService('dynamics/skills.xml') ;

        $skills = xmlService::toJson($xmlService->xmlContent)->skill ;

        foreach($skills as $skill){
            Skill::updateOrInsert(['name' => $skill]);
        }
    }
}
