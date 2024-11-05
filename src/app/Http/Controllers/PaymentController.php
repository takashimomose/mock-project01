<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use App\Http\Requests\PurchaseRequest;

class PaymentController extends Controller
{
    public function checkout(PurchaseRequest $request, $product_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 商品情報を取得
        $product = Product::findOrFail($product_id);

        // $data 配列の構築
        $data = [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_price' => $product->price,
            'method_id' => $request->input('payment_method_id'),
            'delivery_postal_code' => session('delivery_address_data')['postal_code'] ?? null,
            'delivery_address' => session('delivery_address_data')['address'] ?? null,
            'delivery_building' => session('delivery_address_data')['building'] ?? null,
            'order_date' => now(),
        ];

        // セッションにデータを保存
        session(['order_data' => $data]);

        // Stripe Checkout セッションを作成
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->product_name,
                    ],
                    'unit_amount' => $product->price, // Stripeでは最小単位で指定
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['product_id' => $product->id]), // PurchaseControllerのsuccessメソッドにリダイレクト
            'cancel_url' => route('purchase.cancel'),
        ]);

        // Stripeの決済画面にリダイレクト
        return redirect($checkout_session->url);
    }
}
