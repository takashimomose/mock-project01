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
        'user_id',
        'product_name',
        'brand_name',
        'price',
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

    // Categoryとの多対多リレーションを定義
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    // Conditiionとのリレーションを定義
    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }
}
