<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest; // フォームリクエストをインポート
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered; // Registeredイベントをインポート


class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request) // フォームリクエストを受け取る
    {
        $credentials = $request->validated(); // バリデーション済みのデータを取得


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // ユーザーを取得
            $user = Auth::user();

            // email_verified_at カラムが null かどうかをチェック
            if (is_null($user->email_verified_at)) {
                event(new Registered($user));

                // ログアウトしてメール確認ページにリダイレクト
                Auth::logout();
                return redirect()->route('verification.notice');
            }

            // ユーザーの初回ログインかどうかをチェック
            if (is_null($user->last_login_at)) {
                // 初回ログインの場合、プロフィールページへリダイレクト
                $user->last_login_at = now(); // 初回ログイン時間を記録
                $user->save(); // ユーザー情報を保存
                return redirect('/mypage/profile');
            }

            return redirect()->intended('/?tab=mylist');
        }


        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }

    public function destroy(Request $request) // ログアウトの処理
    {
        Auth::logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('login');
    }
}
