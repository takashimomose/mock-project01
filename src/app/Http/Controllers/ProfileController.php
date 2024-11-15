<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        // 現在ログインしているユーザーを取得（ログインしていない場合は null）
        $user = Auth::user();

        // タブの状態を取得
        $tab = $request->get('tab', 'sell'); // デフォルトは'sell'

        // ログインしているユーザーの products テーブルの product_name と product_image を取得
        $productsQuery = Product::where('is_sold', false)
            ->where('user_id', Auth::id()) // ログイン中のユーザーIDで絞り込み
            ->select('id', 'product_name', 'product_image');

        $products = $productsQuery->paginate(8); // ページネーションで8件に制限


        // ログインしているユーザーの購入した商品のproduct_nameとproduct_imageを取得
        $purchasedProductsQuery = Product::where('is_sold', true) // 購入済み商品
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('orders') // ordersテーブルに置き換え
                    ->where('user_id', Auth::id()); // ログインユーザーの購入
            })
            ->select('id', 'product_name', 'product_image'); // 商品名と画像を選択         

        $purchasedProducts = $purchasedProductsQuery->paginate(8); // ページネーションで8件に制限

        // ビューにデータを渡す
        return view('profile', compact('user', 'products', 'purchasedProducts', 'tab'));
    }

    public function showProfileEdit()
    {
        // バリデーションエラーがない場合のみセッションをクリア
        if (!Session::has('errors')) {
            Session::forget('profile_image_path');
        }

        $user = Auth::user(); // 現在ログインしているユーザー情報を取得
        return view('profile-edit', compact('user')); // プロフィールビューを表示
    }

    public function store(Request $request)
    {
        $user = Auth::user(); // 現在のユーザーを取得
        $userData = $request->only(['name', 'postal_code', 'address', 'building', 'profile_image']);

        // 画像ファイルのアップロード
        $path = null;
        if ($request->hasFile('profile_image')) {
            // 画像ファイルが選択されていれば保存
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public'); // publicディスクに保存

            // 画像のパスをセッションに保存
            Session::put('profile_image_path', $path);
        } elseif (Session::has('profile_image_path')) {
            // バリデーションエラー後に再送信した場合、セッションに保存された画像パスを使用
            $path = Session::get('profile_image_path');
        }

        // 商品画像がアップロードされていない場合でも、セッションに保存されたパスを使用して、バリデーションを回避
        $rules = [
            'name' => 'required',
            'profile_image' => 'mimes:jpeg,png',
        ];

        // 画像がアップロードされている場合のみ、画像のバリデーションを追加
        if ($request->hasFile('profile_image')) {
            $rules['profile_image'] = 'required|mimes:jpeg,png';
        }

        // バリデーションの実行
        $request->validate($rules);

        // データベースに画像のパスを保存
        $userData['profile_image'] = $path; // 'profile_images/ファイル名'の形式で保存


        $user->update($userData); // ユーザー情報を更新

        // セッションから画像パスを削除
        Session::forget('profile_image_path');

        return redirect('/?tab=mylist');
    }
}
