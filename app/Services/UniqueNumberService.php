<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Purchase;
use App\Services\BaseService;

/**
 * UniqueNumberService
 */
class UniqueNumberService
{

    public function generatePurchaseUniqueNumber($prefix = 'Pur')
    {
        $date = date('ymd');  
        $random = rand(001, 999);  

        $uniqueNumber =  $date . $random;

        while (Purchase::where('purchase_no', $uniqueNumber)->exists()) {
            $random = rand(001, 999);
            $uniqueNumber = $prefix . $date . $random;
        }

        return $uniqueNumber;
    }

    public function generateOrderUniqueNumber($prefix = 'O-'){
        
        $date = date('ymd');

        $latestOrder = Order::where('order_no', 'like', $date . '%')
                            ->orderBy('order_no', 'desc')
                            ->first();

        if ($latestOrder) {
            
            $lastIncrement = (int) substr($latestOrder->order_no, 7);
            $newIncrement = $lastIncrement + 1;
        } else {
            $newIncrement = 1;
        }

        $incrementPadded = str_pad($newIncrement, 3, '0', STR_PAD_LEFT);

        $orderNumber = $date . $incrementPadded;

        return $orderNumber;
    }

}
