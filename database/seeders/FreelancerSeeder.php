<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skill;
use App\Models\Freelancer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FreelancerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::factory()->makeOne([
            'first_name' => 'freelancer1' ,
            'last_name' => null,
            'email' => 'freelancer1@gmail.com'
        ]);
        $user2 = User::factory()->makeOne([
            'first_name' => 'freelancer2' ,
            'last_name' => null ,
            'email' => 'freelancer2@gmail.com' ,
        ]);

        $freelancer1 = Freelancer::factory()->create();
        $freelancer2 = Freelancer::factory()->create();

        $freelancer1->user()->save($user1) ;
        $freelancer2->user()->save($user2) ;

        $skills = Skill::limit(5)->get() ;

        $freelancer1->skills()->attach($skills) ;
        $freelancer2->skills()->attach($skills) ;
        
    }
}
