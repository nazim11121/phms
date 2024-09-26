<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MiniExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $data;

    public function __construct($data,$totalAgeSum,$totalGirlsSum,$totalBoysSum,$totalNotSum)
    {
        $this->data = $data;
        $this->totalAgeSum = $totalAgeSum;
        $this->totalGirlsSum = $totalGirlsSum;
        $this->totalBoysSum = $totalBoysSum;
        $this->totalNotSum = $totalNotSum;
    }

    public function array(): array
    {
        $data = [];
                                      
        $sl = 1;
        foreach ($this->data as $item) {
                                              
            $r = [];
            // $r[] = $sl;
            $r[] = $item->name ?? '';
            $r[] = $item->total_age ?? '';
            $r[] = $item->girls ?? '';
            $r[] = $item->boys ?? '';
            $r[] = $item->gender_not_mentioned ?? '';
            $data[] = $r;

            // $sl++;
        }

            $r1 = [];
            // $r[] = $sl;
            $r1[] = 'Total';
            $r1[] = $this->totalAgeSum ?? '';
            $r1[] = $this->totalGirlsSum ?? '';
            $r1[] = $this->totalBoysSum ?? '';
            $r1[] = $this->totalNotSum ?? '';
            $data[] = $r1;
           
        return $data;
    }

    public function headings(): array
    {
        return [
            // '#',
            __('District'),
            __('Number'),
            __('Girl'),
            __('Boy'),
            __('Not Mentioned'),
        ];
    }
}