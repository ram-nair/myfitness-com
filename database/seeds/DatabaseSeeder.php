<?php

use App\Admin;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $user = Admin::create(['name' => 'Mobiiworld', 'email' => 'admin@myfamilyfitness.com', 'password' => '123456', 'type' => 'admin']);
        $customer = User::create(['first_name' => 'Customer', 'email' => 'customer@myfamilyfitness.com', 'password' => '123456']);

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'settings']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'user_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'user_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'user_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'user_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'role_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'role_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'role_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'role_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'permission_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'permission_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'permission_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'permission_delete']);

        //store
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_delete']);

        //vendor
        Permission::firstOrCreate(['guard_name' => 'vendor', 'name' => 'store_create']);
        Permission::firstOrCreate(['guard_name' => 'vendor', 'name' => 'store_read']);
        Permission::firstOrCreate(['guard_name' => 'vendor', 'name' => 'store_update']);
        Permission::firstOrCreate(['guard_name' => 'vendor', 'name' => 'store_delete']);

        //brands
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'brand_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'brand_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'brand_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'brand_delete']);

        // create roles and assign existing permissions
        $role = Role::create(['guard_name' => 'admin', 'name' => 'super-admin']);
        $user->assignRole('super-admin');

        $role = Role::create(['guard_name' => 'admin', 'name' => 'admin']);

        $role_store = Role::create(['guard_name' => 'store', 'name' => 'store']);
        $role_vendor = Role::create(['guard_name' => 'vendor', 'name' => 'vendor']);
        $role_customer = Role::create(['guard_name' => 'customer', 'name' => 'customer']);

        $role->givePermissionTo('settings');
        $role->givePermissionTo('user_create');
        $role->givePermissionTo('user_read');
        $role->givePermissionTo('user_update');
        $role->givePermissionTo('user_delete');

        $role->givePermissionTo('role_create');
        $role->givePermissionTo('role_read');
        $role->givePermissionTo('role_update');
        $role->givePermissionTo('role_delete');

        //vendor
        $role->givePermissionTo('vendor_create');
        $role->givePermissionTo('vendor_read');
        $role->givePermissionTo('vendor_update');
        $role->givePermissionTo('vendor_delete');
        //store
        $role->givePermissionTo('store_create');
        $role->givePermissionTo('store_read');
        $role->givePermissionTo('store_update');
        $role->givePermissionTo('store_delete');

        $role_vendor->givePermissionTo('store_create');
        $role_vendor->givePermissionTo('store_read');
        $role_vendor->givePermissionTo('store_update');
        $role_vendor->givePermissionTo('store_delete');

        //call other seeders
        $this->call([
            BusinessTypeTableSeeder::class,
            // BusinessTypecategoryTableSeeder::class,
            // BrandSeeder::class,
            // VendorSeeder::class,
            // CategoriesSeeder::class,
            SettingsTableSeeder::class,
            PermissionsSeeder::class,
            // ProductSeeder::class,
        ]);
    }

}
