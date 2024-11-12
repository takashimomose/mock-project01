<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request; // Requestクラスをインポート
use App\Models\User; // Userモデルをインポート
use App\Http\Controllers\PaymentController;

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

// 認証が必要ないルート
// IndexControllerのindexメソッドを呼び出すルート
Route::get('/', [IndexController::class, 'index'])->name('index');

// ログイン
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// 新規登録
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// 商品詳細ページの表示
Route::get('/item/{product_id}', [ProductController::class, 'show'])->name('product');
// 商品詳細ページのコメントの登録処理
Route::post('/item/{product_id}/comment', [ProductController::class, 'store'])->middleware('auth')->name('comment.store');
// 商品詳細ページのいいねの登録/解除処理
Route::post('/item/{product_id}/like', [ProductController::class, 'toggleLike'])->middleware('auth')->name('product.like');



// 認証済みのユーザーのみがアクセスできるルート
Route::middleware(['auth'])->group(function () {
    // プロフィールページの表示
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile');

    // プロフィール編集ページの表示
    Route::get('/mypage/profile', [ProfileController::class, 'showProfileEdit'])->name('profile.edit');

    // プロフィール情報の更新処理
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.update');

    // 商品出品ページの表示
    Route::get('/sell', [SellController::class, 'show'])->name('sell.show');

    // 商品出品の登録処理
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // 商品購入ページの表示
    Route::get('/purchase/{product_id}', [PurchaseController::class, 'show'])->name('purchase');

    // 商品購入処理
    Route::post('/purchase/{product_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // 送付先住所変更ページの表示
    Route::get('/purchase/address/{product_id}', [PurchaseController::class, 'showDeliveryAddress'])->name('delivery-address.show');

    // 送付先住所変更ページからセッション保存
    Route::post('/purchase/address/{product_id}', [PurchaseController::class, 'storeDeliveryAddress'])->name('delivery-address.store');

    // 支払いと購入
    Route::post('/checkout/{product_id}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::get('/purchase/success/{product_id}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

    // これさっきセッション保存用についかしたやつ
    Route::post('/payment/store/{product_id}', [PurchaseController::class, 'storePaymentMethod'])->name('payment.store');
});

// メール認証
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// ユーザーがメール認証を行うためのルート
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::findOrFail($request->route('id'));

    if (! hash_equals((string) $request->route('hash'), sha1($user->email))) {
        throw new \Illuminate\Validation\ValidationException('Invalid email verification link.');
    }

    $user->markEmailAsVerified();

    return redirect()->route('login')->with('verified', true);
})->middleware(['signed'])->name('verification.verify');