<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    // 商品注文ページの表示
    public function show($product_id)
    {
        // 商品の詳細を取得
        $product = Product::select('id', 'product_image', 'product_name', 'price')
            ->where('id', $product_id)
            ->firstOrFail();

        // 現在ログインしているユーザーの配送先情報を取得
        $user = Auth::user();

        // 支払い方法（payment_methodsテーブルの全レコード）を取得
        $paymentMethods = PaymentMethod::all();

        // ビューにデータを渡す
        return view('purchase', compact('product', 'user', 'paymentMethods'));
    }

    // 送付先住所変更ページの表示
    public function showDeliveryAddress(Request $request, $product_id)
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        return view('delivery-address', compact('user', 'product_id')); // 送付先住所変更ページのビューを表示
    }

    public function storePaymentMethod(Request $request, $product_id)
    {
        $paymentMethodId = $request->input('payment_method_id'); // 直接値を取得

        // セッションに支払い方法を保存
        Session::put('payment_method_id', $paymentMethodId);

        // 支払い方法が変更された後、配送先住所変更ページへリダイレクト
        return redirect()->route('delivery-address.show', ['product_id' => $product_id]);
    }


    // 送付先住所変更ページでのセッション保存
    public function storeDeliveryAddress(AddressRequest $request, $product_id)
    {
        $deliveryAddress = $request->only(['postal_code', 'address', 'building']);

        // セッションにデータを保存
        Session::put('delivery_address_data', $deliveryAddress);

        return redirect()->route('purchase', ['product_id' => $product_id]);
    }

    // Stripe決済成功後の注文保存処理
    public function success(Request $request, $product_id)
    {
        // 現在のユーザーを取得
        $user = Auth::user();

        // セッションから配送先住所を取得
        $deliveryAddress = Session::get('delivery_address_data', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // セッションから注文データを取得
        $orderData = Session::get('order_data');

        // 商品の詳細を取得
        $product = Product::findOrFail($product_id);

        // 新しい注文を作成
        Order::create([
            'user_id' => $orderData['user_id'],
            'product_id' => $product->id,
            'method_id' => $orderData['method_id'],
            'delivery_postal_code' => $deliveryAddress['postal_code'],
            'delivery_address' => $deliveryAddress['address'],
            'delivery_building' => $deliveryAddress['building'],
            'order_date' => Carbon::now(),
        ]);

        // 商品のis_soldをtrueに更新
        $product->update(['is_sold' => true]);

        // 購入後のリダイレクト
        return redirect()->route('index')->with('message', '購入が完了しました。');
    }

    // 決済キャンセル時の処理
    public function cancel()
    {
        return view('cancel');
    }
}
