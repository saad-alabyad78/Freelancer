<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class NoRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        for ($i = 0; $i < 50; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            // استخدام الأسماء العربية لإنشاء البريد الإلكتروني باللغة الإنجليزية
            $email = strtolower($this->transliterate($firstName) . $this->transliterate($lastName) . $i . '@example.com');
            $verifiedAt = $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d H:i:s');

            User::updateOrCreate([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'email_verified_at' => $verifiedAt,
            ], [
                'password' => Hash::make('12345678'),
            ]);
        }
    }

    /**
     * تحويل النص العربي إلى نص لاتيني
     */
    private function transliterate($text)
    {
        $transliterationTable = [
            'أ' => 'a', 'ا' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j', 'ح' => 'h', 'خ' => 'kh',
            'د' => 'd', 'ذ' => 'dh', 'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's',
            'ض' => 'd', 'ط' => 't', 'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'q',
            'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'ه' => 'h', 'و' => 'w', 'ي' => 'y',
            'ء' => 'a', 'ى' => 'a', 'ة' => 'h', 'ئ' => 'a', 'ؤ' => 'a', 'إ' => 'a', 'آ' => 'a',
            ' ' => '_'
        ];

        return strtr($text, $transliterationTable);
    }
}
