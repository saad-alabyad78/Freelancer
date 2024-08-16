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
        $cat1 = Category::updateOrCreate(['name' => 'Web Development']);
        SubCategory::updateOrCreate(['name' => 'Frontend Development', 'category_id' => $cat1->id]);
        SubCategory::updateOrCreate(['name' => 'Backend Development', 'category_id' => $cat1->id]);
        SubCategory::updateOrCreate(['name' => 'Fullstack Development', 'category_id' => $cat1->id]);

        $cat2 = Category::updateOrCreate(['name' => 'Graphic Design']);
        SubCategory::updateOrCreate(['name' => 'UI/UX Design', 'category_id' => $cat2->id]);
        SubCategory::updateOrCreate(['name' => 'Logo Design', 'category_id' => $cat2->id]);
        SubCategory::updateOrCreate(['name' => 'Illustration', 'category_id' => $cat2->id]);

        $cat3 = Category::updateOrCreate(['name' => 'Writing & Translation']);
        SubCategory::updateOrCreate(['name' => 'Copywriting', 'category_id' => $cat3->id]);
        SubCategory::updateOrCreate(['name' => 'Translation', 'category_id' => $cat3->id]);
        SubCategory::updateOrCreate(['name' => 'Editing & Proofreading', 'category_id' => $cat3->id]);

        $cat4 = Category::updateOrCreate(['name' => 'Digital Marketing']);
        SubCategory::updateOrCreate(['name' => 'SEO', 'category_id' => $cat4->id]);
        SubCategory::updateOrCreate(['name' => 'Content Marketing', 'category_id' => $cat4->id]);
        SubCategory::updateOrCreate(['name' => 'Social Media Marketing', 'category_id' => $cat4->id]);

        $cat5 = Category::updateOrCreate(['name' => 'Video & Animation']);
        SubCategory::updateOrCreate(['name' => 'Video Editing', 'category_id' => $cat5->id]);
        SubCategory::updateOrCreate(['name' => '2D Animation', 'category_id' => $cat5->id]);
        SubCategory::updateOrCreate(['name' => '3D Animation', 'category_id' => $cat5->id]);

        $cat6 = Category::updateOrCreate(['name' => 'Music & Audio']);
        SubCategory::updateOrCreate(['name' => 'Music Production', 'category_id' => $cat6->id]);
        SubCategory::updateOrCreate(['name' => 'Voice Over', 'category_id' => $cat6->id]);
        SubCategory::updateOrCreate(['name' => 'Sound Design', 'category_id' => $cat6->id]);

        $cat7 = Category::updateOrCreate(['name' => 'Personal Development']);
        SubCategory::updateOrCreate(['name' => 'Coaching', 'category_id' => $cat7->id]);
        SubCategory::updateOrCreate(['name' => 'Mentorship', 'category_id' => $cat7->id]);
        SubCategory::updateOrCreate(['name' => 'Career Counseling', 'category_id' => $cat7->id]);
    }
}
