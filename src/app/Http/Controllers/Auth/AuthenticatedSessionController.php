<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticatedSessionRequest; // フォームリクエストをインポート
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(AuthenticatedSessionRequest $request) // フォームリクエストを受け取る
    {
        $credentials = $request->validated(); // バリデーション済みのデータを取得


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();


            return redirect()->intended('/admin');
        }


        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/');
    }
}
