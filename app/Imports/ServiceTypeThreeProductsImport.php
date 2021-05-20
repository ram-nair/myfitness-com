<?php

namespace App\Imports;

use App\BusinessTypeCategory;
use App\Category;
use App\ServiceProducts;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class ServiceTypeThreeProductsImport implements OnEachRow, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $rowCount = 0;

    public function checkCategory($category)
    {
        $cat = Category::where('name', $category)->first();
        if ($cat && $cat->catHasChild->count() > 0) {
            return false;
        }
        return true;
    }

    public function rules(): array
    {
        return [
            'business_type_category' => ['required'],
            'service_name' => ['required'],
            'unit_price' => ['required', 'numeric'],
            'max_person' => ['required', 'numeric'],
            'max_hour' => ['required', 'numeric'],

        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        ++$this->rowCount;

        $categoryId = null;
        $byUserId = Auth::user()->id;

        $businessTypeCategory = BusinessTypeCategory::firstOrCreate([
            'name' => $row['business_type_category'],
            'business_type_id' => 3
        ]);
        $businessTypeCategoryId = $businessTypeCategory->id;

//        $category = Category::firstOrCreate([
//            'business_type_category_id' => $businessTypeCategoryId,
//            'is_service' => 1,
//            'service_type' => 'service_type_3',
//            'name' => $row['category']
//        ]);
//        $categoryId = $category->id;
//
//        if (!empty($row['disclaimer'])) {
//            $category->show_disclaimer = 1;
//            $category->disclaimer = $row['disclaimer'];
//            $category->save();
//        }

        $serviceProducts = ServiceProducts::firstOrCreate(
            ['name' => $row['service_name'], 'category_id' => $categoryId, 'service_type' => 3],
            [
                'unit_price' => $row['unit_price'],
                'max_person' => $row['max_person'],
                'max_hour' => $row['max_hour'],
                'description' => $row['description'] ?? null]
        );
        if (!empty($byUserId)) {
            $serviceProducts->by_user_id = $byUserId;
            $serviceProducts->save();
        }
        if (!empty($row['image'])) {
            $serviceProducts->image = $row['image'];
            $serviceProducts->save();
        }
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
