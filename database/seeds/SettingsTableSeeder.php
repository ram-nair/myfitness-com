<?php

use App\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SettingsTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $settings = [
        [
            'key' => 'vat',
            'value' => '5',
        ],
        [
            'key' => 'login_api_url',
            'value' => 'http://myportal.provis.ae/api/Auth/login/',
        ],
        [
            'key' => 'login_api_key',
            'value' => 'cHJvdmlzQVBpS2V5VXNlcjE=',
        ],
        [
            'key' => 'google_api_key',
            'value' => 'AIzaSyD8U8tkj8m2Qv4lLX2O34ufsFbF4pHQkPI',
        ],
        [
            'key' => 'distance_radius',
            'value' => '25',
        ],
        [
            'key' => 'order_cancel_duration',
            'value' => '10',
        ],
        [
            'key' => 'amenity_booking_token',
            'value' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZF91c2VyIjoiMTA5MiIsInVzZXJuYW1lIjoic2VudGhpbC5tQGV4YWxvZ2ljLmNvIiwiaWF0IjoxNTk4MDg4NTQ1LCJleHAiOjE1OTgxMDY1NDV9.8PIjHKydoShcJxfwrcg1InUZJGITubVeiDHCfxJELOY',
        ],
        [
            'key' => 'amenity_booking_url',
            'value' => 'http://stage.realcube.estate/api/AmenityV2/bookAmenity',
        ],
        [
            'key' => 'amenity_cancel_url',
            'value' => 'http://stage.realcube.estate/api/AmenityV2/cancelAmenityBooking',
        ],
        [
            'key' => 'store_contact_fields_count',
            'value' => '4',
        ],
        [
            'key' => 'store_supervisor_contact_fields_count',
            'value' => '4',
        ],
        [
            'key' => 'store_manager_contact_fields_count',
            'value' => '4',
        ],
        [
            'key' => 'class_order_cancel_duration',
            'value' => '120',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'setting_create']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'setting_read']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'setting_update']);
        Permission::firstOrCreate(['guard_name' => 'admin', 'name' => 'setting_delete']);

        foreach ($this->settings as $index => $setting) {
            $result = Setting::create($setting);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted ' . count($this->settings) . ' records');
    }
}
