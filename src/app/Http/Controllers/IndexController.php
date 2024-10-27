<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        // 現在ログインしているユーザーを取得（ログインしていない場合は null）
        $user = Auth::user();

        // 入力された条件を取得
        $keyword = $request->input('keyword');

        // 商品が売れてない商品情報を取得
        $productsQuery = Product::where('is_sold', false)
            ->select('id', 'product_name', 'product_image');

        // ログインしている場合、ユーザーが出品した商品を除外
        if (Auth::check()) {
            $productsQuery->where('user_id', '!=', $user->id);
        }

        // keywordによる検索（部分一致）
        if ($keyword) {
            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            });
        }

        // ページネーションで8件に制限
        $products = $productsQuery->paginate(8);

        // ログインしている場合のみ、いいねした商品のproduct_nameとproduct_imageを取得
        $likedProducts = [];
        if (Auth::check()) { // ユーザーがログインしているか確認
            $likedProductsQuery = Product::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->select('id', 'is_sold', 'product_name', 'product_image');

            // いいねした商品も検索条件でフィルタリング
            if ($keyword) {
                $likedProductsQuery->where(function ($query) use ($keyword) {
                    $query->where('product_name', 'LIKE', "%{$keyword}%");
                });
            }

            $likedProducts = $likedProductsQuery->paginate(8); // ページネーションで8件に制限
        }

        // ビューにデータを渡す
        return view('index', compact('user', 'products', 'likedProducts', 'keyword')); // keywordも渡す
    }
}
