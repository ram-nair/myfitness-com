<?php

use App\BusinessTypeCategory;
use Illuminate\Database\Seeder;

class BusinessTypecategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessTypeCategory::create([
            'business_type_id' => 1,
            'name' => 'Groceries',
            'description' => 'Grocery',
            'image' => '',
        ]);
        BusinessTypeCategory::create([
            'business_type_id' => 1,
            'name' => 'Flowers',
            'description' => 'Flowers',
            'image' => '',
        ]);
        BusinessTypeCategory::create([
            'business_type_id' => 1,
            'name' => 'Pharmacy',
            'description' => 'Pharmacy',
            'image' => '',
        ]);
        BusinessTypeCategory::create([
            'business_type_id' => 3,
            'name' => 'Laundry',
            'description' => 'Laundry',
            'service_type' => 'service_type_1',
        ]);
        BusinessTypeCategory::create([
            'business_type_id' => 3,
            'name' => 'Tailoring',
            'description' => 'Tailoring',
            'service_type' => 'service_type_1',
        ]);
    }
}
