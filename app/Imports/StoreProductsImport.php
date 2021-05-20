<?php

namespace App\Imports;

use App\StoreProduct;
use App\Product;
use App\Store;
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

class StoreProductsImport implements OnEachRow, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    public $stores;
    protected $rowCount = 0;
    public function __construct($stores)
    {
        $this->stores = $stores;
    }


    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required'],
            'stock' => ['required'],
            'unit_price' => ['required'],
            'quantity_per_person' => ['required'],
        ];
    }

    public function onRow(Row $row)
    {
        $row      = $row->toArray();
        ++$this->rowCount;
        $productId = null;

        $product = Product::where('name', $row['product_name'])->first();
        if ($product) {
            foreach ($this->stores as $store) {
                $productId = $product->id;
                StoreProduct::firstOrCreate(
                    ['product_id' => $productId, 'store_id' => $store],
                    ['unit_price' => $row['unit_price'], 'ask_price' => $row['unit_price'], 'stock' => $row['stock'] ?? 0, 'quantity_per_person' => $row['quantity_per_person'] ?? 0]
                );
            }
        }
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
