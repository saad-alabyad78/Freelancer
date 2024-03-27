<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'saad' , 
            'last_name' => 'alabyad' ,
            'email' => 'saadalabyad78@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'password' => Hash::make('12345678') , 
        ]);
        

        $super = SuperAdmin::create() ;

        $super->user()->save($user) ;
        
    }
}
