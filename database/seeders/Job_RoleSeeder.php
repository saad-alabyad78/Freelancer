<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Job_Role;
use App\Services\xmlService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Job_RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xs = new xmlService('dynamics/job_roles.xml') ;

        foreach($xs->xmlContent->job_role as $role)
        {
            $job_roleModel = Job_Role::updateOrCreate(['name' => $role['name']]) ;
            $skillModels = [] ;
            foreach($role->skills->skill as $skill)
            {
                $skillModels[] = Skill::firstOrCreate(['name' => $skill]) ;
            }
            $job_roleModel->skills()->saveMany($skillModels) ;
        }
    }
}
