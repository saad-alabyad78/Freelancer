<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cat1 = Category::updateOrCreate(['name' => 'web design']) ;
            SubCategory::updateOrCreate(['name'=>'ui' , 'category_id' => $cat1->id]);
            SubCategory::updateOrCreate(['name'=>'ui' , 'category_id' => $cat1->id]);

        $cat2 = Category::updateOrCreate(['name' => 'dancing']) ;
            SubCategory::updateOrCreate(['name'=>'western' , 'category_id' => $cat2->id]);
            SubCategory::updateOrCreate(['name'=>'eastern' , 'category_id' => $cat2->id]);

    }
}
