<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // ログインしているユーザーの未販売商品を取得
        $products = Product::getUnsoldProductsByUser(Auth::id());

        // ログインしているユーザーが購入した商品を取得
        $purchasedProducts = Product::getPurchasedProductsByUser(Auth::id());

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

        // プロフィール画像を保存または更新
        $path = $user->saveProfileImage(
            $request->file('profile_image'),
            $user->profile_image ?? Session::get('profile_image_path')
        );

        // セッションに保存された画像パスを削除
        Session::forget('profile_image_path');

        // 必要なバリデーション
        $rules = [
            'name' => 'required',
            'profile_image' => 'nullable|mimes:jpeg,png',
            'postal_code' => 'nullable|regex:/^\d{3}-\d{4}$/',
        ];
        $request->validate($rules);

        // プロフィール情報を更新
        $userData['profile_image'] = $path;
        $user->updateProfile($userData);

        return redirect('/');
    }
}
