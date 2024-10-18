<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;

class SellController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // 現在ログインしているユーザー情報を取得

        // return view('sell', compact('user')); // プロフィールビューを表示

        $categories = Category::all();
        $conditions = Condition::all(); // 条件を取得

        return view('sell', ['categories' => $categories, 'conditions' => $conditions]);
    }

    public function store(ExhibitionRequest $request)
    {
        $sell = $request->only(['product_image', 'category_id', 'condition_id', 'product_name', 'description', 'price']);
        $sell['user_id'] = Auth::id(); // ログインユーザーのIDを追加

        // アップロードされたファイルを取得
        $file = $request->file('product_image');

        // 画像の保存先を指定
        $path = $file->store('product_images', 'public'); // publicディスクに保存

        // データベースに画像のパスを保存
        $sell['product_image'] = $path; // 'product_images/ファイル名'の形式で保存

        Product::create($sell);

        return redirect()->route('index');
    }
}
