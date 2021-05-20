<?php

use App\Admin;
use App\Product;
use App\ServiceProducts;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission::create(['guard_name' => 'admin', 'name' => 'product_create']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'product_read']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'product_update']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'product_delete']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'product_store']);

        // Permission::create(['guard_name' => 'admin', 'name' => 'service_product_create']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'service_product_read']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'service_product_update']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'service_product_delete']);
        // Permission::create(['guard_name' => 'admin', 'name' => 'service_product_store']);

        //store
        // Permission::create(['guard_name' => 'store', 'name' => 'product_read']);
        // Permission::create(['guard_name' => 'store', 'name' => 'product_update']);
        // Permission::create(['guard_name' => 'store', 'name' => 'product_delete']);

        // Permission::create(['guard_name' => 'store', 'name' => 'service_product_read']);
        // Permission::create(['guard_name' => 'store', 'name' => 'service_product_update']);
        // Permission::create(['guard_name' => 'store', 'name' => 'service_product_delete']);

        $admin = Admin::first();

        $product = new Product([
            "name" => "Cocoa Powder - Dutched",
            "brand_id" => 1,
            "sku" => "wqjidW",
            "description" => "Aliquam quis turpis eget elit sodales scelerisque. Mauris sit amet eros. Suspendisse accumsan tortor quis turpis.\n\nSed ante. Vivamus tortor. Duis mattis egestas metus.",
            "category_id" => 2,
            "sub_category_id" => 4,
            "unit_price" => 487,
            "status" => true,
            "featured" => true,
            'by_user_id' => $admin->id,
        ]);
        $product->save();

        $product = new Product([
            "name" => "Oven Mitts 17 Inch",
            "brand_id" => 3,
            "sku" => "boj7AQC",
            "description" => "Donec diam neque, vestibulum eget, vulputate ut, ultrices vel, augue. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec pharetra, magna vestibulum aliquet ultrices, erat tortor sollicitudin mi, sit amet lobortis sapien sapien non mi. Integer ac neque.",
            "unit_price" => 661,
            "category_id" => 5,
            "sub_category_id" => 7,
            "status" => true,
            "featured" => true,
            'by_user_id' => $admin->id,
        ]);
        $product->save();

        $product = new Product([
            "name" => "Gingerale - Diet - Schweppes",
            "brand_id" => 3,
            "sku" => "k03OYZO",
            "description" => "Quisque id justo sit amet sapien dignissim vestibulum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla dapibus dolor vel est. Donec odio justo, sollicitudin ut, suscipit a, feugiat et, eros.",
            "unit_price" => 218,
            "category_id" => 3,
            "sub_category_id" => 9,
            "status" => false,
            "featured" => false,
            'by_user_id' => $admin->id,
        ]);
        $product->save();

        $product = new ServiceProducts([
            "name" => "Shirt",
            "description" => "Quisque id justo sit amet sapien dignissim vestibulum. ",
            "unit_price" => 10,
            "category_id" => 10,
            "status" => 1,
            "featured" => 0,
            'by_user_id' => $admin->id,
        ]);
        $product->save();

        $product = new ServiceProducts([
            "name" => "Pants",
            "description" => "",
            "unit_price" => 20,
            "category_id" => 10,
            "status" => 1,
            "featured" => 0,
            'by_user_id' => $admin->id,
        ]);
        $product->save();

    }
}
