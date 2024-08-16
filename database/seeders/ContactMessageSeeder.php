<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactMessage::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'message' => 'I am interested in your services. Please get back to me.',
        ]);

        ContactMessage::create([
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'phone' => '0987654321',
            'message' => 'I have some questions about your pricing.',
        ]);
    }
}

