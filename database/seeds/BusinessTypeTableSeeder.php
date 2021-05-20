<?php

use Illuminate\Database\Seeder;
use App\BusinessType;

class BusinessTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Business types
        BusinessType::create(['name' => 'E-commerce', 'slug' => 'e-commerce']);
        BusinessType::create(['name' => 'Class', 'slug' => 'class']);
        BusinessType::create(['name' => 'Services', 'slug' => 'services']);
        //fixed businsess types
    }
}
