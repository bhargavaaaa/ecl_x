<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromCollection, WithStyles
{
    private $type = "items";

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->type == "pending-images") {
            $items = Item::with('supplier:id,name', 'sub_category:id,name', 'collection:id,name', 'wastage:id,name', 'gender:id,name', 'plating:id,name', 'size:id,name', 'project:id,name', 'brand:id,name', 'jewel_type:id,name', 'dimension:id,name', 'unit:id,name', 'wastage:id,name', 'hsn:id,name')->select("items.*")->where(function($query) {
                $query->whereNull("image")->orWhere("image", "");
            })->get();
        } else {
            $items = Item::with('supplier:id,name', 'sub_category:id,name', 'collection:id,name', 'wastage:id,name', 'gender:id,name', 'plating:id,name', 'size:id,name', 'project:id,name', 'brand:id,name', 'jewel_type:id,name', 'dimension:id,name', 'unit:id,name', 'wastage:id,name', 'hsn:id,name')->select("items.*")->get();
        }

        $temp = [];
        foreach ($items as $item) {
            $arr = [];
            $arr[] = $item->name;
            $arr[] = $item->mfg_code;
            $arr[] = $item?->supplier?->name ?? "";
            $arr[] = $item?->sub_category?->name ?? "";
            $arr[] = $item?->collection?->name ?? "";
            $arr[] = $item?->gender?->name ?? "";
            $arr[] = $item?->wastage?->name ?? "";
            $arr[] = $item?->brand?->name ?? "";
            $arr[] = $item?->jewel_type?->name ?? "";
            $arr[] = $item?->project?->name ?? "";
            $arr[] = $item?->unit?->name ?? "";
            $arr[] = $item?->dimension?->name ?? "";
            $arr[] = $item?->size?->name ?? "";
            $arr[] = $item?->plating?->name ?? "";
            $arr[] = $item?->hsn?->name ?? "";
            $arr[] = $item->gross_weight;
            $arr[] = $item->less_weight;
            $arr[] = $item->net_weight;
            $arr[] = $item->moq;
            $arr[] = $item->minimum_stock;
            $arr[] = $item->sales_on ? "labor" : "pcs";
            $arr[] = $item->labor_rate;
            $arr[] = $item->pcs_rate;
            $arr[] = $item->description;
            $arr[] = $item->description2;
            $temp[] = $arr;
            unset($arr);
        }

        return collect([["ITEM NAME", "MFG CODE", "SUPPLIER", "SUB CATEGORY", "COLLECTION", "GENDER", "WASTAGE", "BRAND", "JEWEL TYPE", "PROJECT", "UNIT", "DIMENSION", "SIZE", "PLATING", "HSN", "GROSS WEIGHT", "LESS WEIGHT", "NET WEIGHT", "MOQ", "MINIMUM STOCK", "SALES ON", "LABOR RATE", "PCS RATE", "DESCRIPTION", "SECOND DESCRIPTION"]])->merge($temp);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    }
}
