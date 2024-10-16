<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest; // フォームリクエストをインポート
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); // 新規登録ビューを表示
    }

    public function store(RegisterRequest $request) // フォームリクエストを受け取る
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 他の処理（例: ログイン後にリダイレクトなど）

        return redirect()->route('login'); // /login にリダイレクト
    }
}
