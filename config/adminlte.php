<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
     */

    'title' => 'My Family Fitness',
    'title_prefix' => '',
    'title_postfix' => '',
    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
     */
    'use_ico_only' => true,
    'use_full_favicon' => false,
    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
     */
    'logo' => '<b>My Family Fitness</b>',
    'logo_img' => 'vendor/adminlte/dist/img/myfitness_logo.png?ver=1.12',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',
    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
     */
    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-layout
    |
     */
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    /*
    |--------------------------------------------------------------------------
    | Extra Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#66-classes
    |
     */
    'classes_body' => 'text-sm',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_header' => 'container-fluid',
    'classes_content' => 'container-fluid',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-light',
    'classes_topnav_nav' => 'navbar-expand-md',
    'classes_topnav_container' => 'container',
    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-sidebar
    |
     */
    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,
    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-control-sidebar-right-sidebar
    |
     */
    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',
    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-urls
    |
     */
    'use_route_url' => true,
    'dashboard_url' => 'admin.dashboard',
    'logout_url' => 'admin.logout',
    'login_url' => 'admin.login',
    'register_url' => '',
    'password_reset_url' => 'admin.password.request',
    'password_email_url' => 'admin.password.email',
    'profile_url' => 'admin.user.profile',
    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-laravel-mix
    |
     */
    'enabled_laravel_mix' => false,
    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu
    |
     */
    'menu' => [
        [
            'text' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'nav-icon fas fa-tachometer-alt',
        ],

       /* ['header' => 'Marketplace', 'guard' => ['admin'],'can' => ['businesstype_read']],
        [
            'text' => 'General',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            'guard' => ['admin'],
            'can' => ['businesstype_read', 'bussinesstypecategory_read','vendor_read','store_read','slot_read'],
            'submenu' => [
               
              /*  [
                    'text' => 'Business Categories',
                    'url' => 'admin/business-type-categories',
                    'icon' => 'nav-icon fas fa-money-check-alt',
                    'can' => 'bussinesstypecategory_read',
                    'guard' => ['admin'],
                ],
               
                [
                    'text' => 'Stores',
                    'url' => 'admin/stores',
                    'icon' => 'nav-icon fa fa-shopping-cart',
                    'can' => 'store_read',
                    'guard' => ['admin'],
                ],
                
            ],
        ],*/
        [
            'text' => 'Marketplace',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            'guard' => ['store', 'admin'],
            'can' => ['ecomproduct_read'],
            'type' => ['menu_1', 'menu_3'],
            'submenu' => [
                [
                    'text' => 'Category Management',
                    'url' => 'admin/categories',
                    'icon' => 'nav-icon fas fa-money-check-alt',
                    'can' => 'category_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Sub Category Management',
                    'url' => 'admin/subcategories',
                    'icon' => 'nav-icon fa fa-clone',
                    'can' => 'category_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Child Category Management',
                    'url' => 'admin/childcategories',
                    'icon' => 'nav-icon fas fa-money-check-alt',
                    'can' => 'category_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Brand Management',
                    'url' => 'admin/brands',
                    'icon' => 'nav-icon fa fa-window-restore',
                    'can' => 'brand_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Product Management',
                    'url' => 'admin/products',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'ecomproduct_read',
                    'guard' => ['admin'],
                ],
                /*[
                    'text' => 'Store Product Management',
                    'url' => 'admin/store-products',
                    'icon' => 'nav-icon fas fa-inbox',
                    'can' => 'storeproduct_read',
                    'guard' => ['admin'],
                ],*/
                [
                    'text' => 'Product Management',
                    'url' => 'store/store-products',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'ecomproduct_read',
                    'guard' => ['store'],
                    'type' => ['menu_1'],
                ],
                [
                    'text' => 'Manage Gift Cards',
                    'url' => 'admin/gifts',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'categorybanner_read',
                    'guard' => ['admin'],
                ],
                
                [
                    'text' => 'Order Management',
                    'url' => 'store/orders',
                    'icon' => 'nav-icon fa fa-th-large',
                    // 'can' => 'menu_1',
                    'can' => 'order_read',
                    'guard' => ['store'],
                    'type' => ['menu_1'],
                ],
                [
                    'text' => 'Report a Problem',
                    'url' => 'store/report-problem',
                    'icon' => 'nav-icon fa fa-window-restore',
                    'guard' => ['store'],
                    // 'can' => 'menu_1',
                ],
            ],
        ],

        [
            'text' => 'Customer',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            //'can' => ['servicestype1banner_read', 'servicestype2banner_read'],
            'guard' => ['admin'],
            'submenu' => [
                [
                        'text' => 'Customer',
                        //'route' => 'admin.report.user-listing',
                       'url' => 'admin/users',
                       'icon' => 'nav-icon fa fa-th-large',
                       'can' => 'onlineclass_read',
                        'guard' => ['admin'],
                ],
               
                
            ],
        ],
       
        
        ['header' => 'Sales', 'guard' => ['admin'], 'can' => ['servicestype1banner_read', 'servicestype2banner_read']],
        [
            'text' => 'Sales Management',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            //'can' => ['servicestype1banner_read', 'servicestype2banner_read'],
            'guard' => ['admin'],
            'submenu' => [
                [
                        'text' => 'Orders',
                       'url' => 'admin/orders',
                       'icon' => 'nav-icon fa fa-th-large',
                       'can' => 'onlineclass_read',
                        'guard' => ['admin'],
                ],
                [
                    'text' => 'Invoices',
                    'url' => 'admin/invoices',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'categorybanner_read',
                    'guard' => ['admin'],
                ],
                
            ],
        ],
       









        ['header' => 'Banner Management', 'guard' => ['admin'], 'can' => ['servicestype1banner_read', 'servicestype2banner_read']],
        [
            'text' => 'Manage Banner',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            'can' => ['servicestype1banner_read', 'servicestype2banner_read'],
            'guard' => ['admin'],
            'submenu' => [
                [
                    'text' => 'Home Banner(Top)',
                    'url' => 'admin/banners',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'banner_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Home Banner(Middle-1)',
                    'url' => 'admin/banners',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'banner_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Home Banner(Middle-2)',
                    'url' => 'admin/banners',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'banner_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Home Banner(Bottom)',
                    'url' => 'admin/cat-banners',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'banner_read',
                    'guard' => ['admin'],
                ],
                
                [
                    'text' => 'Essentials',
                    'url' => 'admin/cat-banners',
                    'icon' => 'nav-icon fa fa-image',
                    'can' => 'categorybanner_read',
                    'guard' => ['admin'],
                ],

                
                
            ],
        ],
        ['header' => 'Reports', 'guard' => ['admin'],'can' =>['report_read']],

        [
            'text' => 'Manage Reports',
            'icon' => 'nav-icon fas fa-fw fa-bars',
            'can' => 'report_read',
            'guard' => ['admin'],
            'submenu' => [
                [
                    'text' => 'Out Of Stock',
                    'route' => 'admin.report.out-of-stock',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Customer Listing',
                    'route' => 'admin.report.user-listing',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'active' => ['admin/roles', 'admin/roles/*'],
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Purchase History',
                    'route' => 'admin.report.purchase-history',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'active' => ['admin/permissions', 'admin/permissions/*'],
                    'guard' => ['admin'],
                ],
                /*[
                    'text' => 'Avg Prize',
                    'route' => 'admin.report.average-price',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'guard' => ['admin'],
                ],*/
                [
                    'text' => 'Cancelled Order',
                    'route' => 'admin.report.canceled-order',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'guard' => ['admin'],
                ],
                
                /*[
                    'text' => 'Funnel For Order',
                    'route' => 'admin.report.funnel-for-order',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Customer Growth',
                    'route' => 'admin.report.customer-growth',
                    'icon' => 'nav-icon fas fa-chart-line',
                    'can' => 'report_read',
                    'guard' => ['admin'],
                ],*/
            ],
        ],
        ['header' => 'Site settings', 'guard' => ['admin'],'can' =>['user_read','role_read','permission_read']],
        [
            'text' => 'Settings',
            'icon' => 'nav-icon fas fa-fw fa-cogs',
            'can' => 'settings',
            'guard' => ['admin'],
            'submenu' => [
                [
                    'text' => 'Manage Staff',
                    'url' => 'admin/adminusers',
                    'icon' => 'fas fa-fw fa-user-tie',
                    'can' => 'user_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'My Store',
                    'url' => 'admin/stores/517e8990-b9dc-11eb-a247-8926cbd82353/edit',
                    'icon' => 'nav-icon fa fa-shopping-cart',
                    'can' => 'store_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Roles',
                    'route' => 'admin.roles.index',
                    'icon' => 'fas fa-fw fa-tools',
                    'can' => 'role_read',
                    'active' => ['admin/roles', 'admin/roles/*'],
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Permissions',
                    'route' => 'admin.permissions.index',
                    'icon' => 'fas fa-fw fa-key',
                    'can' => 'permission_read',
                    'active' => ['admin/permissions', 'admin/permissions/*'],
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'General',
                    'route' => 'admin.settings.index',
                    'icon' => 'fas fa-fw fa-key',
                    'can' => 'permission_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Content Pages',
                    'url' => 'admin/pages',
                    'icon' => 'fas fa-fw fa-key',
                    'can' => 'category_read',
                    'guard' => ['admin'],
                ],
                [
                    'text' => 'Change Password',
                    'url' => 'admin/changepass',
                    'icon' => 'fas fa-fw fa-key',
                    'can' => 'category_read',
                    'guard' => ['admin'],
                ],
            ],
        ],

/*
        ['header' => 'Manage Offer', 'guard' => ['admin'], 'can' =>['offercategory_read','offerbrand_read']],
        [
            'text' => 'Categories',
            'url' => 'admin/offer-category',
            'icon' => 'nav-icon fa fa-list',
            'can' => 'offercategory_read',
            'guard' => ['admin'],
        ],
        [
            'text' => 'Merchant Management',
            'url' => 'admin/offer-brand',
            'icon' => 'nav-icon fa fa-bandcamp',
            'can' => 'offerbrand_read',
            'guard' => ['admin'],
        ],
        [
            'text' => 'Offers',
            'url' => 'admin/offers',
            'icon' => 'nav-icon fa fa-gift',
            'can' => 'offer_read',
            'guard' => ['admin'],
        ],
         */

        ['header' => 'Manage Blog', 'guard' => ['admin'], 'can' =>['blogcategory_read','blogauthor_read','vlogblog_read']],
        [
            'text' => 'Categories',
            'url' => 'admin/blog-category',
            'icon' => 'nav-icon fa fa-list',
            'can' => 'blogcategory_read',
            'guard' => ['admin'],
        ],
       /* [
            'text' => 'Class & Vlog Vendor',
            'url' => 'admin/blog-author',
            'icon' => 'nav-icon fa fa-user',
            'can' => 'blogauthor_read',
            'guard' => ['admin'],
        ],*/
        [
            'text' => 'Blogs',
            'url' => 'admin/blog',
            'icon' => 'nav-icon fas fa-blog',
            'can' => 'vlogblog_read',
            'guard' => ['admin'],
        ],

        [
            'text' => 'Product Management',
            'url' => 'store/service-store-products',
            'icon' => 'nav-icon fas fa-chart-line',
            'guard' => ['store'],
            'type' => ['menu_3'],
            // 'can' => 'menu_3',
        ],
        [
            'text' => 'Order Management',
            'url' => 'store/service-orders',
            'icon' => 'nav-icon fa fa-th-large',
            'guard' => ['store'],
            'type' => ['menu_3'],
            // 'can' => 'menu_3',
        ],
        
        [
            'text' => 'Report a Problem',
            'url' => 'store/report-problem',
            'icon' => 'nav-icon fa fa-window-restore',
            'guard' => ['store'],
            'type' => ['menu_3', 'menu_1'],
        ],

        // ['header' => 'Marketplace Classes', 'guard' => ['admin'], 'can' => ['onlineclass_read', 'category_read', 'package_read']],
        // [
        //     'text' => 'Catalog Management',
        //     'url' => 'admin/class-categories',
        //     'icon' => 'nav-icon fa fa-sitemap',
        //     'can' => 'category_read',
        //     'guard' => ['admin'],
        // ],
        // [
        //     'text' => 'Package Management',
        //     'url' => 'admin/packages',
        //     'icon' => 'nav-icon fa fa-archive',
        //     'can' => 'package_read',
        //     'guard' => ['admin'],
        // ],
        // [
        //     'text' => 'Online Classes',
        //     'url' => 'admin/generalclass?type=online',
        //     'icon' => 'nav-icon fa fa-graduation-cap',
        //     'can' => 'onlineclass_read',
        //     'guard' => ['admin'],
        // ],
        // [
        //     'text' => 'Offline Classes',
        //     'url' => 'admin/generalclass?type=offline',
        //     'icon' => 'nav-icon fa fa-university',
        //     'can' => 'onlineclass_read',
        //     'guard' => ['admin'],
        // ],
        // [
        //     'text' => 'Orders',
        //     'url' => 'admin/orders',
        //     'icon' => 'nav-icon fa fa-th-large',
        //     'can' => 'onlineclass_read',
        //     'guard' => ['admin'],
        // ],


    ],
    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#612-menu-filters
    |
     */
    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        // JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
        App\Myadmin\MyMenuFilter::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#613-plugins
    |
     */
    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/dataTables.responsive.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/responsive.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables/css/responsive.bootstrap4.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/select2/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2/css/select2.min.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/chart.js/Chart.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/sweetalert2/sweetalert.min.js',
                ],
            ],
        ],
        [
            'name' => 'summernote',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js',
                ],
            ],
        ],
        [
            'name' => 'BootstrapSwitch',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-switch/js/bootstrap-switch.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/pace-progress/themes/blue/pace-theme-flat-top.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/pace-progress/pace.min.js',
                ],
            ],
        ],
    ],
];
