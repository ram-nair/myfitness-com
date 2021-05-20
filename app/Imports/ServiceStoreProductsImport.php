<?php

namespace App\Imports;

use App\ServiceProducts;
use App\Store;
use App\StoreServiceProducts;
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

class ServiceStoreProductsImport implements OnEachRow, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithChunkReading
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
            'unit_price' => ['required'],
        ];
    }

    public function onRow(Row $row)
    {
        $row  = $row->toArray();
        ++$this->rowCount;
        $productId = null;

        $product = ServiceProducts::where('name', $row['product_name'])->first();
        if ($product) {
            foreach ($this->stores as $store) {
                $productId = $product->id;
                StoreServiceProducts::firstOrCreate(
                    ['product_id' => $productId, 'store_id' => $store],
                    ['unit_price' => $row['unit_price'], 'ask_price' => $row['unit_price']]
                );
            }
        }
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
