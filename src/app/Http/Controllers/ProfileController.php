<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得
        return view('profile-edit', compact('user')); // プロフィールビューを表示
    }

    public function store(Request $request)
    {
        $user = Auth::user(); // 現在のユーザーを取得
        $userData = $request->only(['name', 'postal_code', 'address', 'building', 'profile_image']);

        // 画像ファイルがアップロードされたかチェック
        if ($request->hasFile('profile_image')) {
            // アップロードされたファイルを取得
            $file = $request->file('profile_image');

            // 画像の保存先を指定
            $path = $file->store('profile_images', 'public'); // publicディスクに保存

            // データベースに画像のパスを保存
            $userData['profile_image'] = $path; // 'profile_images/ファイル名'の形式で保存
        }

        $user->update($userData); // ユーザー情報を更新

        return redirect('/?tab=mylist');
    }
}
