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
use App\StoreProduct;

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
        $brand =Brand::where('name',$row['brand_name'])->first();
        if (!$brand) {
            $brand = Brand::firstOrCreate([
            "name" => $row['brand_name'],
        ]);
        }
        $brandId = $brand->id;
        $category = Category::where('name',$row['category'])->first();
        if (!$category) {
            $category = Category::firstOrCreate([
            'name' => $row['category']
        ]);
        }
        $categoryId = $category->id;
        $subCategory = Category::where('name',$row['sub_category'])->where('parent_cat_id', $categoryId)->first();
        if (! $subCategory) {
            $subCategory = Category::firstOrCreate([
            'name' => $row['sub_category'],
            'parent_cat_id' => $categoryId
        ]);
        }
        $subCategoryId = $subCategory->id;
        $childcategory = Category::where('name',$row['child_category'])->where('parent_cat_id', $subCategoryId)->first();
        if (! $childcategory) {
            $childcategory = Category::firstOrCreate([
            'name' => $row['child_category'],
            'parent_cat_id' => $subCategoryId
        ]);
        }
        $child_category_id = $childcategory->id;
        $product = Product::firstOrCreate(
            ['name' => $row['product_name'], 'category_id' => $categoryId, 'sub_category_id' => $subCategoryId,'child_category_id'=>$child_category_id],
            ['sku' => $row['sku'],'meta_title'=> $row['product_name'],'meta_tag'=> $row['product_name'],'brand_id' => $brandId, 'unit_price' => $row['unit_price'], 'quantity' => $row['quantity'] ?? 0, 'short_description' => $row['description'] ?? null,'description' => $row['description'] ?? null]
        );
        if (!empty($byUserId)) {
            $product->by_user_id = $byUserId;
            $product->save();
        }
        if (!empty($row['image'])) {
            $images=explode(',',$row['image']);
            foreach($images as $img){
               $product->images()->create(['full' => $img]);
            }
            
            ///$product->images()->attach(['full'=>$images]);
        }
         
        $StoreProduct =new StoreProduct();
        $StoreProduct->store_id='517e8990-b9dc-11eb-a247-8926cbd82353';
        $StoreProduct->product_id=$product->id;
        $StoreProduct->save();
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
