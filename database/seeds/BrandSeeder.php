<?php

use App\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'name' => 'Brand 1',
            'image' => '',
            'description' => 'Brand 1 Description',
        ]);
        Brand::create([
            'name' => 'Brand 2',
            'image' => '',
            'description' => 'Brand 2 Description',
        ]);
        Brand::create([
            'name' => 'Brand 3',
            'image' => '',
            'description' => 'Brand 3 Description',
        ]);
    }
}
