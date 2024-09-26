<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * SerialPurchasesReportExport
 */
class AllDataExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $items;

    /**
     * __construct
     *
     * @param  mixed $items
     * @return void
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * array
     *
     * @return array
     */

    public function array(): array
    {
        $data = [];

        $sl = 1;
        foreach ($this->items as $item) {
            $r = [];
            $r[] = $sl;
            $r[] = $item->name ?? '';
            $r[] = $item->phone ?? '';
            $r[] = $this->pickup_address($item) ?? '';
            $r[] = $this->delivery_address($item) ?? '';
            // $r[] = $this->date($item) ?? '';

            $data[] = $r;

            $sl++;
        }

        return $data;
    }

    public function pickup_address($item)
    {
        return $item->customer->pickup_address ?? '';
    }
    
    public function delivery_address($item)
    {
        return $item->customer->delivery_address ?? '';
    }

    public function date($item)
    {
        return date('d-m-Y',strtotime($item->created_at));
    }

    /**
     * headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            __('Name'),
            __('Contact Number'),
            __('Pickup Address'),
            __('Delivery Address'),
            // __('Entry Date'),
        ];
    }
}
