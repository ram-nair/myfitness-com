<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //category
        // Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'category_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'category_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'category_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'category_delete']);

        //banner
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'banner_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'banner_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'banner_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'banner_delete']);

        //slot
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'slot_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'slot_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'slot_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'slot_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'slot_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'slot_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'slot_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'slot_delete']);

        //business type
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'businesstype_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'businesstype_update']);

        //bussiness_type_category
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'bussinesstypecategory_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'bussinesstypecategory_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'bussinesstypecategory_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'bussinesstypecategory_delete']);

        //ecom products
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'ecomproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'ecomproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'ecomproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'ecomproduct_delete']);

        //ecom products
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'ecomproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'ecomproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'ecomproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'ecomproduct_delete']);

        //service_type1_products
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype1product_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype1product_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype1product_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype1product_delete']);

        //servicetype2_products
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype2product_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype2product_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype2product_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype2product_delete']);

        //servicetype2_products
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype3product_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype3product_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype3product_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicetype3product_delete']);

        //storeproduct
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'storeproduct_delete']);

        //storeproduct
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'storeproduct_delete']);

        //categorybanner
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'categorybanner_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'categorybanner_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'categorybanner_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'categorybanner_delete']);

        //servicetype1storeproducts
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1storeproduct_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype1storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype1storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype1storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype1storeproduct_delete']);

        //servicetype2storeproducts
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2storeproduct_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype2storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype2storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype2storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype2storeproduct_delete']);

        //servicetype2storeproducts
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype3storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype3storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype3storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype3storeproduct_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype3storeproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype3storeproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype3storeproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'servicestype3storeproduct_delete']);

        //service banners
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1banner_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1banner_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1banner_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype1banner_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2banner_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2banner_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2banner_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'servicestype2banner_delete']);

        //menu perms
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vendor_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'vendor_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'vendor_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'vendor_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'vendor_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'store_delete']);

        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'store_create']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'store_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'store_update']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'store_delete']);

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'order_read']);
        Permission::firstOrCreate(['guard_name' => 'store', 'name' => 'order_read']);

        //online classes
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'onlineclass_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'onlineclass_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'onlineclass_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'onlineclass_delete']);

        //offline classes
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offlineclass_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offlineclass_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offlineclass_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offlineclass_delete']);

        //class packages
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'package_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'package_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'package_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'package_delete']);

        //report
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'report_read']);

        //blog category
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogcategory_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogcategory_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogcategory_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogcategory_delete']);

        //blog author category
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogauthor_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogauthor_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogauthor_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'blogauthor_delete']);

        //vlog blog
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vlogblog_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vlogblog_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vlogblog_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'vlogblog_delete']);

        //offer category
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offercategory_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offercategory_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offercategory_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offercategory_delete']);

        //offer Brand
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerbrand_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerbrand_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerbrand_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerbrand_delete']);


        //offers
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offer_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offer_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offer_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offer_delete']);


        //catalog management
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'catalogmanagement_class']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'catalogmanagement_services']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'catalogmanagement_ecommerce']);

        //offers
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerproduct_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerproduct_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerproduct_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'offerproduct_delete']);


        //assign to admin role
        $allPermIds = Permission::where('guard_name', 'admin')->get()->pluck('id');
        //give superadmin all perms
        $role = Role::where('name', 'super-admin')->first();
        $role->givePermissionTo($allPermIds);

        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo($allPermIds);

        //assign stores
        $allPermIds = Permission::where('guard_name', 'store')->get()->pluck('id');
        $role = Role::where('name', 'store')->first();
        $role->givePermissionTo($allPermIds);
    }
}
