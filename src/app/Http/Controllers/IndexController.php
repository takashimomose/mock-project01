<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; // Productモデルを使用
use App\Models\Like;    // Likeモデルを使用

class IndexController extends Controller
{
    public function index(Request $request) // Requestオブジェクトを追加
    {
        // 現在ログインしているユーザーを取得（ログインしていない場合は null）
        $user = Auth::user();

        // 入力された条件を取得
        $keyword = $request->input('keyword');

        // is_soldがtrueではないproductsテーブルのproduct_nameとproduct_imageを取得
        $productsQuery = Product::where('is_sold', false)
            ->select('product_name', 'product_image');

        // keywordによる検索（部分一致）
        if ($keyword) {
            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('product_name', 'LIKE', "%{$keyword}%");
            });
        }

        $products = $productsQuery->paginate(8); // ページネーションで8件に制限

        // ログインしている場合のみ、いいねした商品のproduct_nameとproduct_imageを取得
        $likedProducts = [];
        if (Auth::check()) { // ユーザーがログインしているか確認
            $likedProductsQuery = Product::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->select('is_sold', 'product_name', 'product_image');

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
