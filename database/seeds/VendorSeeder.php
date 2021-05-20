<?php

use App\Admin;
use App\Store;
use App\Vendor;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Slot;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $admin = Admin::first();
        // dd($admin);
        $vendor1 = Vendor::create([
            'name' => 'Pothys',
            'description' => 'Pothys Hyper market',
            'email' => "pothysvendor@provis.com",
            'password' => '123456',
            'by_user_id' => $admin->id,
        ]);
        $vendor1->assignRole('vendor');
        
        $vendor2 = Vendor::create([
            'name' => 'Lulu',
            'description' => 'Lulu Hyper market',
            'email' => "luluvendor@provis.com",
            'password' => '123456',
            'by_user_id' => $admin->id,
        ]);
        $vendor2->assignRole('vendor');
        $store1 = Store::create([
            'business_type_id' => 1,
            'business_type_category_id' => 1,
            'name' => 'Pothys TVM',
            'description' => 'Pothys TVM Hyper market',
            'email' => "pothystvm@provis.com",
            'password' => '123456',
            'latitude' => 523.52,
            'longitude' => 45.342,
            'location' => "Trivandrum",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\Admin",
            'vendor_id' => $vendor1->id,
        ]);
        $store1->assignRole('store');

        $store2 = Store::create([
            'business_type_id' => 1,
            'business_type_category_id' => 1,
            'name' => 'Pothys Chennai',
            'description' => 'Pothys Hyper market',
            'email' => "pothyschennai@provis.com",
            'password' => '123456',
            'latitude' => 523.52,
            'longitude' => 45.342,
            'location' => "Chennai",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\\Admin",
            'vendor_id' => $vendor1->id,
        ]);
        $store2->assignRole('store');

        $store3 = Store::create([
            'business_type_id' => 1,
            'business_type_category_id' => 1,
            'name' => 'Lulu Mall trivandrum',
            'description' => 'Lulu trivandrum ongoing',
            'email' => "lulutvm@provis.com",
            'password' => '123456',
            'latitude' => 523.52,
            'longitude' => 45.342,
            'location' => "Cochin",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\\Admin",
            'vendor_id' => $vendor2->id,
        ]);
        $store3->assignRole('store');

        $store4 = Store::create([
            'business_type_id' => 3,
            'business_type_category_id' => 4,
            'service_type' => 'service_type_1',
            'name' => 'ABC Dry Cleaners',
            'description' => 'dry cleaning store',
            'email' => "abcdry@provis.com",
            'password' => '123456',
            'latitude' => '10.850684492309083',
            'longitude' => '76.27194160688477',
            'location' => "Cochin",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\\Admin",
            'vendor_id' => $vendor2->id,
        ]);
        $store4->assignRole('store');

        $store5 = Store::create([
            'business_type_id' => 3,
            'business_type_category_id' => 4,
            'service_type' => 'service_type_1',
            'name' => 'XYZ bcd Dry Cleaners',
            'description' => 'dry cleaning store',
            'email' => "xyzdry@provis.com",
            'password' => '123456',
            'latitude' => '10.850684492309083',
            'longitude' => '76.27194160688477',
            'location' => "Mannathala",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\\Admin",
            'vendor_id' => $vendor2->id,
        ]);
        $store5->assignRole('store');

        $store5 = Store::create([
            'business_type_id' => 3,
            'business_type_category_id' => 5,
            'service_type' => 'service_type_1',
            'name' => 'ABC Tailors',
            'description' => 'Tailoring service store',
            'email' => "abctailor@provis.com",
            'password' => '123456',
            'latitude' => '8.481099700000001',
            'longitude' => '76.9455815',
            'location' => "Eastfort Trivandrum",
            'by_user_id' => $admin->id,
            'by_user_type' => "App\\Admin",
            'vendor_id' => $vendor2->id,
        ]);
        $store5->assignRole('store');

        $slots = array('10 am to 11 am','10.30 am to 12:30', '11 am to 3pm','03.30 pm to 6pm');
        foreach($slots as $slot){
            $slot = Slot::create([
                'name' => $slot, 
                'by_user_id' => $admin->id
            ]);
        }
    }
}
