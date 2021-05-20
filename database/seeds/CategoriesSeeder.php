<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::create(['guard_name' => 'admin', 'name' => 'category_create']);
        Permission::create(['guard_name' => 'admin', 'name' => 'category_read']);
        Permission::create(['guard_name' => 'admin', 'name' => 'category_update']);
        Permission::create(['guard_name' => 'admin', 'name' => 'category_delete']);

        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Fruits',
            'description' => 'Fruits',
            'parent_cat_id' => 0,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Dairy & Eggs',
            'description' => 'Dairy products',
            'parent_cat_id' => 0,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Bakery',
            'description' => 'Bakery',
            'parent_cat_id' => 0,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Smoking',
            'description' => 'Smoking',
            'parent_cat_id' => 0,
            'show_disclaimer' => 1,
            'disclaimer' => "Smoking is injurious to health, tobacco causes cancer",
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Tools',
            'description' => 'Tools',
            'parent_cat_id' => 0,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Cheese',
            'description' => 'Cheese',
            'parent_cat_id' => 2,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Fresh Milk',
            'description' => 'Fresh Milk',
            'parent_cat_id' => 2,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Dry Fruits',
            'description' => 'Dry Fruits',
            'parent_cat_id' => 1,
        ]);
        Category::create([
            'business_type_category_id'=>1,
            'name'=>'Citrus',
            'description' => 'Citrus',
            'parent_cat_id' => 1,
        ]);
        Category::create([
            'business_type_category_id' => 4,
            'name'=>'Steaming',
            'description' => 'Steaming',
            'parent_cat_id' => 0,
            'is_service' => 1,
            'service_type' => 'service_type_1',
        ]);
        Category::create([
            'business_type_category_id' => 4,
            'name'=>'Dry Cleaning',
            'description' => 'Dry Cleaning',
            'parent_cat_id' => 0,
            'is_service' => 1,
            'service_type' => 'service_type_1',
        ]);
    }
}
