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

    // いいねのトグル処理
    public static function toggleLike($productId, $userId)
    {
        // いいねが存在するか確認
        $like = self::where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            // いいねが存在する場合は削除
            $like->delete();
        } else {
            // いいねが存在しない場合は追加
            self::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }
    }
}
