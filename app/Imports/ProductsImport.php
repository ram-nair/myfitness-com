<?php

namespace App\Imports;

use App\Product;
use App\Brand;
use App\BusinessTypeCategory;
use App\Category;
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

class ProductsImport implements OnEachRow, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $rowCount = 0;

    public function rules(): array
    {
        return [
            'business_type_category' => ['required'],
            'product_name' => ['required'],
            'sku' => ['required', 'unique:products,sku'],
            'category' => ['required'],
            'sub_category' => ['required'],
            'unit_price' => ['required'],
            'quantity' => ['required'],
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

        $brandId = null;
        $businessTypeCategoryId = null;
        $categoryId = null;
        $subCategoryId = 0;
        $byUserId = Auth::user()->id;
        $brand = Brand::firstOrCreate([
            "name" => $row['brand_name'],
        ]);
        $brandId = $brand->id;

        $businessTypeCategory = BusinessTypeCategory::firstOrCreate([
            'name' => $row['business_type_category'],
            'business_type_id' => 1,
        ]);
        $businessTypeCategoryId = $businessTypeCategory->id;

        $category = Category::firstOrCreate([
            'business_type_category_id' => $businessTypeCategoryId,
            'name' => $row['category']
        ]);
        $categoryId = $category->id;

        if (!empty($row['category_disclaimer'])) {
            $category->show_disclaimer = 1;
            $category->disclaimer = $row['category_disclaimer'];
            $category->save();
        }

        $subCategory = Category::firstOrCreate([
            'business_type_category_id' => $businessTypeCategoryId,
            'name' => $row['sub_category'],
            'parent_cat_id' => $categoryId
        ]);
        $subCategoryId = $subCategory->id;

        if (!empty($row['sub_category_disclaimer'])) {
            $subCategory->show_disclaimer = 1;
            $subCategory->disclaimer = $row['sub_category_disclaimer'];
            $subCategory->save();
        }

        $product = Product::firstOrCreate(
            ['name' => $row['product_name'], 'category_id' => $categoryId, 'sub_category_id' => $subCategoryId],
            ['sku' => $row['sku'], 'brand_id' => $brandId, 'unit_price' => $row['unit_price'], 'quantity' => $row['quantity'] ?? 0, 'description' => $row['description'] ?? null, 'unit' => $row['unit']]
        );
        if (!empty($byUserId)) {
            $product->by_user_id = $byUserId;
            $product->save();
        }
        if (!empty($row['image'])) {
            $product->images()->create(['full' => $row['image']]);
        }
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
