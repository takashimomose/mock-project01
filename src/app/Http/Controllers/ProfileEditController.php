<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileEditController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得
        return view('profile-edit', compact('user')); // プロフィールビューを表示
    }

    public function store(Request $request)
    {
        $user = Auth::user(); // 現在のユーザーを取得
        $userData = $request->only(['name', 'postal_code', 'address', 'building', 'profile_image']);
        $user->update($userData); // ユーザー情報を更新

        return redirect()->route('profile.edit');
    }
}
