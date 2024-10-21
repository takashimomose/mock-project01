<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session; 

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
    public function showDeliveryAddress($product_id)
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        return view('delivery-address', compact('user', 'product_id')); // 送付先住所変更ページのビューを表示
    }
    // // 送付先住所変更処理
    public function storeDeliveryAddress(AddressRequest $request, $product_id)
    {
        
        $deliveryAddress = $request->only(['postal_code', 'address', 'building']);

        // セッションにデータを保存
        Session::put('delivery_address_data', $deliveryAddress);

        return redirect()->route('purchase', ['product_id' => $product_id]);

    }
}
