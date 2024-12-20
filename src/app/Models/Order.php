<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    // テーブル名
    protected $table = 'orders';

    // 複数代入を許可するカラム
    protected $fillable = [
        'user_id',
        'user_name',
        'product_id',
        'product_name',
        'product_price',
        'method_id',
        'delivery_postal_code',
        'delivery_address',
        'delivery_building',
        'order_date',
    ];

    // 日付フィールドのキャスト
    protected $casts = [
        'order_date' => 'datetime',
    ];

    // リレーション: 注文は1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // リレーション: 注文は1つの商品に属する
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // リレーション: 注文は1つの支払い方法に属する
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    // 注文を作成するメソッド
    public static function createOrder($orderData, $deliveryAddress, $productId)
    {
        return self::create([
            'user_id' => $orderData['user_id'],
            'product_id' => $productId,
            'method_id' => $orderData['method_id'],
            'delivery_postal_code' => $deliveryAddress['postal_code'],
            'delivery_address' => $deliveryAddress['address'],
            'delivery_building' => $deliveryAddress['building'],
            'order_date' => Carbon::now(),
        ]);
    }
}
