<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }

    /**
     * 条件に基づく商品一覧を取得
     *
     * @param string|null $keyword
     * @param int|null $excludeUserId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getFilteredProducts($keyword = null, $excludeUserId = null)
    {
        $query = self::select('id', 'is_sold', 'product_name', 'product_image');

        // ログインユーザーの商品を除外
        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        // キーワード検索
        if ($keyword) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        return $query;
    }

    /**
     * ログインユーザーがいいねした商品を取得
     *
     * @param int $userId
     * @param string|null $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getLikedProducts($userId, $keyword = null)
    {
        $query = self::whereHas('likes', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->select('id', 'is_sold', 'product_name', 'product_image');

        // キーワード検索
        if ($keyword) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        return $query;
    }
}
