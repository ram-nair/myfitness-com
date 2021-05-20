<?php

use Illuminate\Database\Seeder;
use App\ClearingMaterials;
class ClearingMaterial extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClearingMaterials::firstOrCreate(['title' => 'BRING CLEARING MATERIALS', 'description' => 'CLEARING MATERIALS']);

    }
}
