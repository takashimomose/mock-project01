<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'likes';

    // 保存可能なカラムを指定
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // いいねはユーザーに属する（user_id とのリレーション）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // いいねは商品に属する（product_id とのリレーション）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
