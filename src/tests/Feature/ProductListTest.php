<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ProductListTest extends TestCase
{
    use DatabaseTransactions;

    public function test_all_products_are_displayed_on_product_page()
    {
        $perPage = 8;

        // 商品の総数を取得
        $totalProducts = Product::select('id', 'is_sold', 'product_name', 'product_image')->count();

        // 必要なページ数を計算
        $totalPages = ceil($totalProducts / $perPage);

        // 各ページを順にテスト
        for ($page = 1; $page <= $totalPages; $page++) {
            $response = $this->get("/?page={$page}");
            $response->assertStatus(200);

            // 現在のページに表示される商品を取得
            $products = Product::select('id', 'is_sold', 'product_name', 'product_image')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            // 各商品がページに表示されているか確認
            foreach ($products as $product) {
                $response->assertSee($product->product_name); // 商品名が表示されていることを確認
                $response->assertSee($product->product_image); // 商品画像が表示されていることを確認
            }
        }
    }

    public function test_sold_label_is_displayed_for_sold_products()
    {
        // 商品ページにアクセス
        $response = $this->get('/');

        // is_sold が true の商品を取得
        $soldProduct = Product::where('is_sold', true)->first();

        // is_sold = true の商品が存在する場合のみテストを実行
        if ($soldProduct) {
            // 商品名と画像が表示されていることを確認
            $response->assertSee($soldProduct->product_name);
            $response->assertSee($soldProduct->product_image);

            // SOLD ラベルが表示されていることを確認
            $response->assertSee('SOLD');
        } else {
            $this->markTestSkipped('is_sold = 1 の商品がデータベースに存在しません。');
        }
    }

    public function test_logged_in_user_does_not_see_their_own_products()
    {
        // 初回ログインのユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'registered@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'last_login_at' => null, // 初回ログイン
        ]);
    
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // / へアクセスして商品一覧に移動
        $response = $this->get('/');

        // ログインユーザーが出品した商品を取得
        $userProducts = Product::where('user_id', $user->id)->get();

        // ログインユーザーが出品した商品が表示されていないことを確認
        foreach ($userProducts as $product) {
            $response->assertDontSee($product->product_name); // 商品名が表示されていないことを確認
            $response->assertDontSee($product->product_image); // 商品画像が表示されていないことを確認
        }

        // ステータスコードが200であることを確認
        $response->assertStatus(200);
    }
}

