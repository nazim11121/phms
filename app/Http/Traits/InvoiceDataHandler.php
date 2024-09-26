<?php

namespace App\Http\Traits;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderSkuList;
use App\Models\ShippingAddress;

trait InvoiceDataHandler {

    public function getInvoiceData($order) {
        $isInvoiceExist = Order::where(['order_no' => $order->id])->first();

        // if (empty($isInvoiceExist)) {
        //     $invoicePrint          = new Invoice;
        //     $invoicePrint->order_no = $order->id;
        //     $invoicePrint->status  = 1;
        //     $invoicePrint->save();
        // }

        $shippingAddress = $order->shippingAddress != null ? ShippingAddress::with('deliveryAddress.districtInfo')->find($order->shippingAddress) : null;

        $deliveryPartner = $order->shippingFee;

        $generator = "SS";
        $barcode   = "HH";

        return [
            'id'              => $order->id,
            'orderId'         => $order->order_no,
            'barcode'         => "N/A",
            'date'            => date('d F, Y', strtotime($order->created_at)),
            'status'          => $order->status,
            'phone'           => $order->user->phone,
            'name'            => $order->user->name,
            'email'           => $order->user->email,
            'delivery_address'=> $order->delivery_address,
            'pickup_address'  => $order->pickup_address,
            'discount'        => $order->discount,
            'discount_amount' => $order->discount_amount,
            'subtotal'        => $order->subtotal,
            'grandtotal'      => $order->grand_total,

            'orderDetails'    => OrderSkuList::with(['service','category'])->where(['order_id' => $order->id])->orderBy('id', 'desc')->get()->map(function ($od) {
                // $thumbnail = $od->productDetails->product->thumbnail ? $od->productDetails->product->thumbnail : '';
                return [
                    'orderDetailId' => $od->id,
                    'name'          => $od->service->name,
                    'category'      => $od->category->name,
                    // 'thumbnail'     => $od->productDetails->product->thumbnail ? "storage/images/products/thumbnail/" . $thumbnail : 'storage/images/blank.jpg',
                    'price'         => $od->price,
                    'quantity'      => $od->quantity,
                    'subtotal'      => $od->subtotal,
                    // 'images'        => count($od->productDetails->productImages) ? "storage/images/products/details/" . $od->productDetails->productImages[0]->image : 'storage/images/blank.jpg',
                ];
            }),
        ];

    }
}
