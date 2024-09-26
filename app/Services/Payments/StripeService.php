<?php

namespace App\Services\Payments;


/**
 * StripeService
 */
class StripeService
{
    protected $secret;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->secret =  config('stripe.secret_key');
    }

    /**
     * pay
     *
     * @param  mixed $invoice
     * @return void
     */
    public function pay($invoice)
    {

        $stripe = new \Stripe\StripeClient($this->secret);

        $session =  $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => config('stripe.currency_code'),
                    'product_data' => [
                        'name' => 'Invoice-' . $invoice->id,
                    ],
                    'unit_amount' => $invoice->last_paid * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['invoice_id' => $invoice->id]),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return $session->url;
    }
}
