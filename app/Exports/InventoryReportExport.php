<?php

namespace App\Exports;

use App\Models\Inventory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryReportExport implements FromCollection, WithStyles
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $inventories = Inventory::with('item:id,name', 'site:id,name', 'box:id,name', 'purity:id,name', 'wastage:id,name')->get();

        $temp = [];
        foreach ($inventories as $inventory) {
            $arr = [];
            $arr[] = $inventory->id;
            $arr[] = $inventory->transactable_id;
            $arr[] = preg_replace('/([a-z])([A-Z])/', '$1 $2', str_replace("App\\Models\\", "", $inventory->transactable_type));
            $arr[] = $inventory->item->name;
            $arr[] = $inventory->site->name;
            $arr[] = $inventory->box->name;
            $arr[] = $inventory->quantity;
            $arr[] = $inventory->gross_weight;
            $arr[] = $inventory->less_weight;
            $arr[] = $inventory->net_weight;
            $arr[] = $inventory->purity->name;
            $arr[] = $inventory->purity_value;
            $arr[] = $inventory->wastage->name;
            $arr[] = $inventory->box_weight;
            $arr[] = $inventory->bag_weight;
            $arr[] = $inventory->tag_weight;
            $arr[] = $inventory->pad_weight;
            $arr[] = $inventory->total_gross_weight;
            $arr[] = $inventory->labor_rate;
            $arr[] = $inventory->total_amount;
            $temp[] = $arr;
            unset($arr);
        }

        return collect([["Id", "Transaction Id", "Transaction Type", "Item", "Site", "Box", "Quantity", "Gross Weight", "Less Weight", "Net Weight", "Purity", "Purity Value", "Wastage", "Box Weight", "Bag Weight", "Tag Weight", "Pad Weight", "Total Gross Weight", "Labor Rate", "Total Amount"]])->merge($temp);
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }
}
