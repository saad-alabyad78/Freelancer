<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Services\xmlService;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlService = new xmlService('dynamics/skills.xml');

        $skills = xmlService::toJson($xmlService->xmlContent)->skill;

        foreach($skills as $skill) {
            Skill::updateOrInsert(['name' => (string)$skill]);
        }
    }
}
