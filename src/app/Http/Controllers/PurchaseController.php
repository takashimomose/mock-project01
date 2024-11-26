<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    // 商品注文ページの表示
    public function show($product_id)
    {
        // 商品の詳細を取得
        $product = Product::getProductDetails($product_id);

        // 現在ログインしているユーザーの配送先情報を取得
        $user = Auth::user()->getShippingInfo();

        // 支払い方法（payment_methodsテーブルの全レコード）を取得
        $paymentMethods = PaymentMethod::all();

        // セッションから支払い方法IDを取得
        $payment_method_id = Session::get('payment_method_id');

        // ビューにデータを渡す
        return view('purchase', compact('product', 'user', 'paymentMethods', 'payment_method_id'));
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

        // 現在ログイン中のユーザーを取得
        $user = Auth::user();

        // usersテーブルのpostal_code, address, buildingがnullの場合にデータを保存
        if ($user) {
            $updateData = [];

            if (is_null($user->postal_code)) {
                $updateData['postal_code'] = $deliveryAddress['postal_code'];
            }
            if (is_null($user->address)) {
                $updateData['address'] = $deliveryAddress['address'];
            }
            if (is_null($user->building)) {
                $updateData['building'] = $deliveryAddress['building'];
            }

            // 必要なデータがあれば更新
            if (!empty($updateData)) {
                $user->update($updateData);
            }
        }

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
        Order::createOrder($orderData, $deliveryAddress, $product->id);

        // 商品のis_soldをtrueに更新
        $product->markAsSold();

        // 購入後のリダイレクト
        return redirect()->route('index')->with('message', '購入が完了しました。');
    }


    // 決済キャンセル時の処理
    public function cancel()
    {
        return view('cancel');
    }
}
