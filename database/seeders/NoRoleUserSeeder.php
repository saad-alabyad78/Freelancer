<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NoRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Create([
            'first_name' => 'saad' , 
            'last_name' => 'alabyad' ,
            'email' => 'saadalabyad1999@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'password' => Hash::make('12345678') , 
        ]);
    }
}
