<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;

class ProductController extends Controller
{
    // 商品の詳細ページ
    public function show($product_id)
    {
        // productと関連するcategoriesとconditionを取得する（Eager Loading）
        $product = Product::with('categories', 'condition')->findOrFail($product_id);
        // 取得したデータをビューに渡す
        return view('product', compact('product'));
    }

    public function store(Request $request, $product_id)
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        // コメントデータを準備
        $comment = [
            'comment' => $request->input('comment'),
            'user_id' => $user->id, // ログインユーザーのID
            'product_id' => $product_id, // 商品のID
        ];

        // データベースにコメントを保存
        Comment::create($comment);

        // 商品詳細ページにリダイレクト
        return redirect()->route('product', ['product_id' => $product_id]);
    }
}
