<?php

namespace ACFBentveld\Shop\Webhooks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ShopOrderCreated;
use Mollie;
use Mail;
/**
 * An laravel Shop package
 *
 */
class MollieController extends Controller {

    /**
     * Handle the webhook. Update the order
     *
     * @param Request $request
     * @return void
     */
    public function handle(Request $request) {
        if (! $request->has('id')) {
            return;
        }
        $payment = Mollie::api()->payments()->get($request->id);
        $model = config('shop.order-model');
        $order = $model::where('payment_id', $request->id)->first();
        $cart = \Shop::cart($order->user_id);
        \Shop::order()->moveCartToOrder($order, $order->user_id);
        if($payment->isPaid()){
            $cart->cleanCart($order->user_id);
            $this->sendMail($order);
        }
        $order->update(['status' => $payment->status, 'method' => $payment->method]);
    }

    /**
     * Send email to the customer with the order status
     *
     * @param type $order
     */
    private function sendMail($order)
    {
        try {
            Mail::to($order->user->email)->send(new ShopOrderCreated($order));
        } catch (\Exception $e) {
        }
    }
    
}