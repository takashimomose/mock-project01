<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest; // フォームリクエストをインポート
use App\Models\User;
use Illuminate\Auth\Events\Registered; // Registeredイベントをインポート
use Illuminate\Support\Facades\Hash;

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

        // Registeredイベントを発火して認証メールを送信
        event(new Registered($user));

        return redirect()->route('verification.notice'); // 認証待ちページにリダイレクト
    }
}
