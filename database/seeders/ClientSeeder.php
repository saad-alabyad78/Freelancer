<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Constants\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::create(
            [
                'gender' => Gender::MALE ,
                'date_of_birth' => Carbon::now()->toDateString(),
                'city' => 'دمشق',
                'username' => 'client-client-1' ,
            ]
        ) ;

        $user = User::updateOrCreate([
            'first_name' => 'client' , 
            'last_name' => 'client' ,
            'email' => 'client@gmail.com' ,
            'email_verified_at' => '2000-11-11' ,
            'role_id' => $client->id ,
            'role_type' => Client::class ,
            ] , [
                'password' => Hash::make('12345678') , 
            ]) ;

    }
}
