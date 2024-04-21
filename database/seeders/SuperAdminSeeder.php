<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = 
        [
            User::create([
            'first_name' => 'saad' , 
            'last_name' => 'alabyad' ,
            'email' => 'saadalabyad78@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'password' => Hash::make('12345678') , 
        ]) ,
        
            User::create([
            'first_name' => 'ياسر' , 
            'last_name' => 'جمال الدين' ,
            'email' => 'yasserjamalaldeen@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'password' => Hash::make('12345678') , 
        ]) 
        ] ;
        

        foreach($users as $user)
        {
            $super = SuperAdmin::create()  ;
            $super->user()->save($user) ;
        }
    }
}
