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
        User::updateOrCreate([
            'first_name' => 'saad' , 
            'last_name' => 'alabyad' ,
            'email' => 'saadalabyad1999@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            ] , [
                'password' => Hash::make('12345678') , 
            ]) ;

        User::updateOrCreate([
            'first_name' => 'saad' , 
            'last_name' => 'alabyad' ,
            'email' => 'saadalabyad2000@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            ] , [
                'password' => Hash::make('12345678') , 
            ]) ;
            
        User::updateOrCreate([
        'first_name' => 'saad' , 
        'last_name' => 'alabyad' ,
        'email' => 'saadalabyad78@gmail.com' ,
        'email_verified_at' => '2000-11-11' ,
        ] , [
            'password' => Hash::make('12345678') , 
        ]) ;
    
        User::updateOrCreate([
        'first_name' => 'ياسر' , 
        'last_name' => 'جمال الدين' ,
        'email' => 'yasserjamalaldeen@gmail.com' ,
        'email_verified_at' => '2000-11-11' ,
        ] , [
            'password' => Hash::make('12345678') , 
        ]) ;
    
        User::updateOrCreate([
        'first_name' => 'صلاح' , 
        'last_name' => 'التيناوي' ,
        'email' => 'azy3449@gmail.com' ,
        'email_verified_at' => '2000-11-11' ,
        ] , [
            'password' => Hash::make('12345678') , 
        ]) ;
    
        User::updateOrCreate([
        'first_name' => 'شام' , 
        'last_name' => 'جاموس' ,
        'email' => 'Shamjamous7@gmail.com' ,
        'email_verified_at' => '2000-11-11' ,
        ] , [
            'password' => Hash::make('12345678') , 
        ]) ;
    } 
    
}
