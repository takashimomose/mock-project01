<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;

class ProductController extends Controller
{
    // 商品の詳細ページ
    public function show($product_id)
    {
        // 商品、カテゴリ、状態を取得
        $product = Product::getProductWithDetails($product_id);

        // コメントを取得
        $comments = $product->comments()->with('user')->get();

        // コメント数を取得
        $commentCount = $product->getCommentCount();

        // いいね数を取得
        $likeCount = $product->getLikeCount();

        // ユーザーがその商品に「いいね」をしているか確認
        $isLiked = $product->isLikedByUser(Auth::id());

        // 取得したデータをビューに渡す
        return view('product', compact('product', 'comments', 'commentCount', 'likeCount', 'isLiked'));
    }

    public function store(CommentRequest $request, $product_id)
    {
        // 現在ログインしているユーザーを取得
        $user = Auth::user();

        // コメントデータを準備
        $comment = [
            'comment' => $request->input('comment'),
            'user_id' => $user->id, // ログインユーザーのID
            'product_id' => $product_id, // 商品のID
        ];

        // Commentモデルを使ってコメントを保存
        Comment::storeComment($comment);

        // 商品詳細ページにリダイレクト
        return redirect()->route('product', ['product_id' => $product_id]);
    }

    public function toggleLike(Request $request, $product_id)
    {
        $userId = Auth::id(); // 現在のユーザーIDを取得

        // LikeモデルのtoggleLikeメソッドを呼び出し
        Like::toggleLike($product_id, $userId);

        // 商品詳細ページにリダイレクト
        return redirect()->route('product', ['product_id' => $product_id]);
    }
}
