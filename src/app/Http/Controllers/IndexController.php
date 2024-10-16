<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得
        return view('index', compact('user')); // プロフィールビューを表示
    }
}
