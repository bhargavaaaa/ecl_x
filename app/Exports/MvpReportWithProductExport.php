<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MvpReportWithProductExport implements FromCollection, WithStyles
{
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $temp = [];
        foreach ($this->items as $item) {
            $arr = [];
            $arr[] = $item->name;
            $arr[] = $item->product;
            $arr[] = $item->quantity;
            $arr[] = $item->gross_weight;
            $arr[] = $item->less_weight;
            $arr[] = $item->net_weight;
            $temp[] = $arr;
            unset($arr);
        }

        return collect([["Name", "Product", "Quantity", "Gross Weight", "Less Weight", "Net Weight"]])->merge($temp);
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }
}
