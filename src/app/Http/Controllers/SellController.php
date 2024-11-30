<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class SellController extends Controller
{
    public function show()
    {
        // バリデーションエラーがない場合のみセッションをクリア
        if (!Session::has('errors')) {
            Session::forget('product_image_path');
        }

        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        $categories = Category::getAllCategories();
        $conditions = Condition::getAllConditions();

        return view('sell', compact('user', 'categories', 'conditions'));
    }

    public function store(Request $request)
    {
        // 画像ファイルのアップロード
        $path = null;
        if ($request->hasFile('product_image')) {
            // 画像ファイルが選択されていれば保存
            $file = $request->file('product_image');
            $path = Product::uploadImage($file);

            // 画像のパスをセッションに保存
            Session::put('product_image_path', $path);
        } elseif (Session::has('product_image_path')) {
            // バリデーションエラー後に再送信した場合、セッションに保存された画像パスを使用
            $path = Session::get('product_image_path');
        }

        // 商品画像がアップロードされていない場合でも、セッションに保存されたパスを使用して、バリデーションを回避
        $rules = [
            'product_image' => 'required',
            'categories' => 'required|array',
            'condition_id' => 'required',
            'product_name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
        ];

        // 画像がアップロードされている場合のみ、画像のバリデーションを追加
        if ($request->hasFile('product_image')) {
            $rules['product_image'] = 'required|mimes:jpeg,png';
        }

        // バリデーションの実行
        $request->validate($rules);

        // データベースに保存するデータ
        $sell = $request->only(['condition_id', 'product_name', 'description', 'price']);
        $sell['user_id'] = Auth::id();
        $sell['product_image'] = $path ?? Session::get('product_image_path');

        // 商品をデータベースに保存
        $product = Product::create($sell);

        // カテゴリーテーブルへの関連付け
        $category_ids = $request->input('categories');
        $product->attachCategories($category_ids);

        // セッションから画像パスを削除
        Session::forget('product_image_path');

        // 完了したらリダイレクト
        return redirect()->route('index');
    }
}
