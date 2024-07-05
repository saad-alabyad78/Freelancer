<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::create() ;

        $user = User::updateOrCreate([
            'first_name' => 'admin' , 
            'last_name' => 'admin' ,
            'email' => 'admin@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'role_id' => $admin->id ,
            'role_type' => Admin::class ,
            ] , [
                'password' => Hash::make('12345678') , 
            ]) ;

    }
}
