<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'comments';

    // 保存可能なカラムを指定
    protected $fillable = [
        'comment',
        'user_id',
        'product_id',
    ];

    // コメントはユーザーに属する（user_id とのリレーション）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントは商品に属する（product_id とのリレーション）
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // コメントを保存するメソッド
    public static function storeComment($commentData)
    {
        return self::create($commentData);
    }
}
