<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'products';

    // 保存可能なカラムを指定
    protected $fillable = [
        'product_name',
        'brand_name',
        'price',
        'category_id',
        'condition_id',
        'is_sold',
        'description',
        'product_image',
    ];

    // likesテーブルとのリレーションを定義
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
