<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\JobRole;
use App\Services\xmlService;
use Illuminate\Database\Seeder;

class Job_RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xs = new xmlService('dynamics/job_roles.xml');

        foreach ($xs->xmlContent->job_role as $role) {
            $job_roleModel = JobRole::updateOrCreate(['name' => (string)$role->attributes()->name]);

            $skillIds = [];
            foreach ($role->skills->skill as $skill) {
                $skillModel = Skill::firstOrCreate(['name' => (string)$skill]);
                $skillIds[] = $skillModel->id;
            }

            $job_roleModel->skills()->sync($skillIds);
        }
    }
}
