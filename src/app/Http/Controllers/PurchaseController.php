<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
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
    public function showDeliveryAddress($product_id)
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        return view('delivery-address', compact('user', 'product_id')); // 送付先住所変更ページのビューを表示
    }
    // 送付先住所変更ページでのセッション保存
    public function storeDeliveryAddress(AddressRequest $request, $product_id)
    {

        $deliveryAddress = $request->only(['postal_code', 'address', 'building']);

        // セッションにデータを保存
        Session::put('delivery_address_data', $deliveryAddress);

        return redirect()->route('purchase', ['product_id' => $product_id]);
    }

    // 商品購入ページでの購入
    public function store(PurchaseRequest $request, $product_id)
    {
        // 現在のユーザーを取得
        $user = Auth::user();

        // セッションから配送先住所を取得
        $deliveryAddress = Session::get('delivery_address_data', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // 商品の詳細を取得
        $product = Product::findOrFail($product_id);

        // 新しい注文を作成
        Order::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_price' => $product->price,
            'method_id' => $request->input('paymentMethod_id'), // 支払い方法の選択
            'delivery_postal_code' => $deliveryAddress['postal_code'],
            'delivery_address' => $deliveryAddress['address'],
            'delivery_building' => $deliveryAddress['building'],
            'order_date' => Carbon::now(), // 現在の日時
        ]);

        // ユーザーのis_soldをtrueに更新
        $product->update(['is_sold' => true]);

        // 購入後のリダイレクト
        return redirect()->route('index'); // 適切なリダイレクト先を設定
    }
}
