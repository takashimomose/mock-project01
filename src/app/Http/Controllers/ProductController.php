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
        // productと関連するcategoriesとconditionを取得する（Eager Loading）
        $product = Product::with('categories', 'condition')->findOrFail($product_id);

        // コメントを取得し、ユーザー情報も一緒に取得する（Eager Loading）
        $comments = Comment::with('user') // コメントしたユーザーの情報を取得
            ->where('product_id', $product_id)
            ->get();

        // コメント数を取得
        $commentCount = $comments->count();

        // いいね数を取得
        $likeCount = Like::where('product_id', $product_id)->count();

        // ユーザーがその商品に「いいね」をしているか確認
        $isLiked = Like::where('product_id', $product_id)->where('user_id', Auth::id())->exists();

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

        // データベースにコメントを保存
        Comment::create($comment);

        // 商品詳細ページにリダイレクト
        return redirect()->route('product', ['product_id' => $product_id]);
    }

    public function toggleLike(Request $request, $product_id)
    {
        $userId = Auth::id(); // 現在のユーザーIDを取得

        // いいねが存在するか確認
        $like = Like::where('product_id', $product_id)->where('user_id', $userId)->first();

        if ($like) {
            // いいねが存在する場合は削除
            $like->delete();
        } else {
            // いいねが存在しない場合は追加
            Like::create([
                'user_id' => $userId,
                'product_id' => $product_id,
            ]);
        }

        // 商品詳細ページにリダイレクト
        return redirect()->route('product', ['product_id' => $product_id]);
    }
}
