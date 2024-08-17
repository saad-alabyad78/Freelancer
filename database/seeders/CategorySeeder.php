<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web Development' => [
                'Frontend Development',
                'Backend Development',
                'Fullstack Development',
            ],
            'Graphic Design' => [
                'UI/UX Design',
                'Logo Design',
                'Illustration',
            ],
            'Writing & Translation' => [
                'Copywriting',
                'Translation',
                'Editing & Proofreading',
            ],
            'Digital Marketing' => [
                'SEO',
                'Content Marketing',
                'Social Media Marketing',
            ],
            'Video & Animation' => [
                'Video Editing',
                '2D Animation',
                '3D Animation',
            ],
            'Music & Audio' => [
                'Music Production',
                'Voice Over',
                'Sound Design',
            ],
            'Personal Development' => [
                'Coaching',
                'Mentorship',
                'Career Counseling',
            ],

            'تطوير الويب' => [
                'تطوير الواجهة الأمامية',
                'تطوير الواجهة الخلفية',
                'تطوير الويب المتكامل',
            ],
            'التصميم الجرافيكي' => [
                'تصميم واجهة المستخدم وتجربة المستخدم',
                'تصميم الشعارات',
                'الرسوم التوضيحية',
            ],
            'الكتابة والترجمة' => [
                'كتابة المحتوى',
                'الترجمة',
                'التحرير والتدقيق اللغوي',
            ],
            'التسويق الرقمي' => [
                'تحسين محركات البحث',
                'تسويق المحتوى',
                'التسويق عبر وسائل التواصل الاجتماعي',
            ],
            'الفيديو والرسوم المتحركة' => [
                'تحرير الفيديو',
                'الرسوم المتحركة ثنائية الأبعاد',
                'الرسوم المتحركة ثلاثية الأبعاد',
            ],
            'الموسيقى والصوت' => [
                'إنتاج الموسيقى',
                'التعليق الصوتي',
                'تصميم الصوت',
            ],
            'التطوير الشخصي' => [
                'التدريب',
                'الإرشاد',
                'الإرشاد المهني',
            ],
        ];


        foreach ($categories as $categoryName => $subCategories) {
            // Create or update the category
            $category = Category::updateOrCreate(['name' => $categoryName]);

            // Loop through the subcategories and create or update them
            foreach ($subCategories as $subCategoryName) {
                SubCategory::updateOrCreate(['name' => $subCategoryName, 'category_id' => $category->id]);
            }
        }

    }
}
