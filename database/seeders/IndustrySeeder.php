<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            'Information Technology',
            'Healthcare',
            'Education',
            'Manufacturing',
            'Finance',
            'Retail',
            'Real Estate',
            'Hospitality',
            'Transportation',
            'Construction',
            'Marketing & Advertising',
            'Telecommunications',
            'Entertainment',
            'Legal Services',
            'Non-Profit',
            'Public Administration',
            'تكنولوجيا المعلومات',
            'الرعاية الصحية',
            'التعليم',
            'التصنيع',
            'المالية',
            'التجزئة',
            'العقارات',
            'الضيافة',
            'النقل',
            'البناء',
            'التسويق والإعلان',
            'الاتصالات',
            'الترفيه',
            'الخدمات القانونية',
            'المنظمات غير الربحية',
            'الإدارة العامة',
        ];
        
        foreach ($industries as $industry) {
            Industry::updateOrCreate(['name' => $industry]);
        }
        
    }
}
