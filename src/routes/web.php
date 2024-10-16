<?php

use App\Http\Controllers\ProfileEditController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

// 以下はプロフィール編集画面からデータ挿入
// Route::post('/profiles', [ProfileEditController::class, 'store'])->name('index');

// 以下はログイン用
// 認証が必要ないルート
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// 以下は新規登録用
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// 認証済みのユーザーのみがアクセスできるルート
Route::middleware(['auth'])->group(function () {
    // プロフィール編集ページの表示
    Route::get('/mypage/profile', [ProfileEditController::class, 'index'])->name('profile.edit');

    // プロフィール情報の更新処理
    Route::post('/mypage/profile', [ProfileEditController::class, 'store'])->name('profile.update');
});


// Route::get('/mypage/profile', [ProfileController::class, 'index'])->name('profile-edit');
