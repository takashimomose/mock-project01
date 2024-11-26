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

    /**
     * ログインユーザーの未販売商品を取得
     *
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getUnsoldProductsByUser($userId)
    {
        return self::where('is_sold', false)
            ->where('user_id', $userId)
            ->select('id', 'product_name', 'product_image')
            ->paginate(8);
    }

    /**
     * ログインユーザーが購入した商品を取得
     *
     * @param int $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPurchasedProductsByUser($userId)
    {
        return self::where('is_sold', true)
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('product_id')
                    ->from('orders')
                    ->where('user_id', $userId);
            })
            ->select('id', 'product_name', 'product_image')
            ->paginate(8);
    }

    // 商品画像アップロード処理
    public static function uploadImage($file)
    {
        if ($file) {
            return $file->store('product_images', 'public');
        }
        return null;
    }

    // 商品とカテゴリの関連付け
    public function attachCategories($categoryIds)
    {
        if (is_array($categoryIds)) {
            $this->categories()->attach($categoryIds);
        }
    }

    // 商品に関連するカテゴリと状態を取得する
    public static function getProductWithDetails($productId)
    {
        return self::with('categories', 'condition')->findOrFail($productId);
    }

    // コメントの件数を取得する
    public function getCommentCount()
    {
        return $this->comments()->count();
    }

    // 商品に関連するコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいねの件数を取得する
    public function getLikeCount()
    {
        return $this->likes()->count();
    }

    // ユーザーがその商品に「いいね」をしているか確認
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
