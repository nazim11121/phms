<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $data;

    public function __construct($data,$violenceList,$violenceList2)
    {
        $this->data = $data;
        $this->violenceList = $violenceList;
        $this->violenceList2 = $violenceList2;
    }

    public function array(): array
    {
        $data = [];
        
        $ftotal_age_0_6 = 0;
        $ftotal_age_7_12 = 0;
        $ftotal_age_13_18 = 0;
        $ftotal_age_not = 0;
        $ftotal_age = 0;
        $ftotal_cases_filed = 0;
        $ftotal_boys = 0;
        $ftotal_girls = 0;
        $ftotal_gender_not = 0;

        $cftotal_age_0_6 = 0;
        $cftotal_age_7_12 = 0;
        $cftotal_age_13_18 = 0;
        $cftotal_age_not = 0;
        $cftotal_age = 0;
        $cftotal_cases_filed = 0;
        $cftotal_boys = 0;
        $cftotal_girls = 0;
        $cftotal_gender_not = 0;
                                          
        $sl = 1;
        foreach ($this->violenceList as $item) {
            
            $total_age_0_6 = 0;
            $total_age_7_12 = 0;
            $total_age_13_18 = 0;
            $total_age_not = 0;
            $total_age = 0;
            $total_cases_filed = 0;
            $total_boys = 0;
            $total_girls = 0;
            $total_gender_not = 0;
            foreach($this->data as $output){
                if($item->name==$output->violence_nature){
                    $total_age_0_6 += $output->age_0_6;
                    $total_age_7_12 += $output->age_7_12;
                    $total_age_13_18 += $output->age_13_18;
                    $total_age_not += $output->age_not_mentioned;
                    $total_age += $output->total_age;
                    $total_cases_filed += $output->cases_filed;
                    $total_boys += $output->boys;
                    $total_girls += $output->girl;
                    $total_gender_not += $output->gender_not_mentioned;
                }
            }
                                              
            $r = [];
            // $r[] = $sl;
            $r[] = $item->name ?? '';
            $r[] = $total_age_0_6 ?? '';
            $r[] = $total_age_7_12 ?? '';
            $r[] = $total_age_13_18 ?? '';
            $r[] = $total_age_not ?? '';
            $r[] = $total_age ?? '';
            $r[] = $total_cases_filed ?? '';
            $r[] = $total_boys ?? '';
            $r[] = $total_girls ?? '';
            $r[] = $total_gender_not ?? '';
            $data[] = $r;

            // $sl++;

            $ftotal_age_0_6 += $total_age_0_6;
            $ftotal_age_7_12 += $total_age_7_12;
            $ftotal_age_13_18 += $total_age_13_18;
            $ftotal_age_not += $total_age_not;
            $ftotal_age += $total_age;
            $ftotal_cases_filed += $total_cases_filed;
            $ftotal_boys += $total_boys;
            $ftotal_girls += $total_girls;
            $ftotal_gender_not += $total_gender_not;
        }

        $r1[] = "Total";
        $r1[] = $ftotal_age_0_6 ?? '';
        $r1[] = $ftotal_age_7_12 ?? '';
        $r1[] = $ftotal_age_13_18 ?? '';
        $r1[] = $ftotal_age_not ?? '';
        $r1[] = $ftotal_age ?? '';
        $r1[] = $ftotal_cases_filed ?? '';
        $r1[] = $ftotal_boys ?? '';
        $r1[] = $ftotal_girls ?? '';
        $r1[] = $ftotal_gender_not ?? '';
        $data[] = $r1;

        $r0[] = "";
        $data[] = $r0;
        // return $data;
        foreach ($this->violenceList2 as $item) {
            
            $ctotal_age_0_6 = 0;
            $ctotal_age_7_12 = 0;
            $ctotal_age_13_18 = 0;
            $ctotal_age_not = 0;
            $ctotal_age = 0;
            $ctotal_cases_filed = 0;
            $ctotal_boys = 0;
            $ctotal_girls = 0;
            $ctotal_gender_not = 0;
            foreach($this->data as $output){
                if($item->name==$output->violence_nature){
                    $ctotal_age_0_6 += $output->age_0_6;
                    $ctotal_age_7_12 += $output->age_7_12;
                    $ctotal_age_13_18 += $output->age_13_18;
                    $ctotal_age_not += $output->age_not_mentioned;
                    $ctotal_age += $output->total_age;
                    $ctotal_cases_filed += $output->cases_filed;
                    $ctotal_boys += $output->boys;
                    $ctotal_girls += $output->girl;
                    $ctotal_gender_not += $output->gender_not_mentioned;
                }
            }
                                              
            $r2 = [];
            // $r[] = $sl;
            $r2[] = $item->name ?? '';
            $r2[] = $ctotal_age_0_6 ?? '';
            $r2[] = $ctotal_age_7_12 ?? '';
            $r2[] = $ctotal_age_13_18 ?? '';
            $r2[] = $ctotal_age_not ?? '';
            $r2[] = $ctotal_age ?? '';
            $r2[] = $ctotal_cases_filed ?? '';
            $r2[] = $ctotal_boys ?? '';
            $r2[] = $ctotal_girls ?? '';
            $r2[] = $ctotal_gender_not ?? '';

            $data[] = $r2;

            // $sl++;

            $cftotal_age_0_6 += $ctotal_age_0_6;
            $cftotal_age_7_12 += $ctotal_age_7_12;
            $cftotal_age_13_18 += $ctotal_age_13_18;
            $cftotal_age_not += $ctotal_age_not;
            $cftotal_age += $ctotal_age;
            $cftotal_cases_filed += $ctotal_cases_filed;
            $cftotal_boys += $ctotal_boys;
            $cftotal_girls += $ctotal_girls;
            $cftotal_gender_not += $ctotal_gender_not;
        }

        $r12[] = "Total";
        $r12[] = $cftotal_age_0_6 ?? '';
        $r12[] = $cftotal_age_7_12 ?? '';
        $r12[] = $cftotal_age_13_18 ?? '';
        $r12[] = $cftotal_age_not ?? '';
        $r12[] = $cftotal_age ?? '';
        $r12[] = $cftotal_cases_filed ?? '';
        $r12[] = $cftotal_boys ?? '';
        $r12[] = $cftotal_girls ?? '';
        $r12[] = $cftotal_gender_not ?? '';
        $data[] = $r12;
        return $data;
    }

    public function headings(): array
    {
        return [
            // '#',
            __('Nature of Violence'),
            __('0-6'),
            __('7_12'),
            __('13-18'),
            __('Age Not Mentioned'),
            __('Cases Filed'),
            __('Girls'),
            __('Boys'),
            __('Gender Not Mentioned'),
        ];
    }
}