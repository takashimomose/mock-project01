<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class IndexController extends Controller
{
    private const ITEMS_PER_PAGE = 8;

    public function index(Request $request)
    {
        // 現在ログインしているユーザー
        $user = Auth::user();
        $keyword = $request->input('keyword');

        // 商品一覧取得
        $userId = $user ? $user->id : null;
        $products = Product::getFilteredProducts($keyword, $userId)
            ->paginate(self::ITEMS_PER_PAGE);

        // ユーザーがログインしている場合、いいねした商品を取得
        $likedProducts = [];
        if ($user) {
            $likedProducts = Product::getLikedProducts($user->id, $keyword)
                ->paginate(self::ITEMS_PER_PAGE);
        }

        // ビューにデータを渡す
        return view('index', compact('user', 'products', 'likedProducts', 'keyword'));
    }
}
