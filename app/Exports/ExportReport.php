<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
class ExportReport implements FromCollection, WithTitle,WithHeadings
{
    public $reportDetail;
    public $header;
    public function __construct($data,$header)
    {
        $this->reportDetail = $data;
        $this->header = $header;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->reportDetail);
    }

    public function title(): string
    {
        return 'Report Detail';
    }
    public function headings(): array
    {
        return $this->header;
    }
}
