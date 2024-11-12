<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class MyListTest extends TestCase
{
    use DatabaseTransactions;

    public function test_logged_in_user_does_not_see_liked_products()
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

        // / へアクセスしてマイリストに移動
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        // 商品の総数を取得（この例では「いいね」した商品）
        $likedProductsCount = Product::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // 商品一覧の1ページに表示される商品の数
        $perPage = 8;

        // 必要なページ数を計算
        $totalPages = ceil($likedProductsCount / $perPage);

        // 各ページを順にテスト
        for ($page = 1; $page <= $totalPages; $page++) {
            // 対象ページにアクセス
            $response = $this->get("/?page={$page}");

            // ステータスコードが200であることを確認
            $response->assertStatus(200);

            // ログインユーザーが「いいね」した商品を取得
            $likedProducts = Product::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->select('id', 'product_name', 'product_image')
                ->skip(($page - 1) * $perPage) // ページネーションに基づくスキップ
                ->take($perPage) // ページごとの制限
                ->get();

            // いいねした商品が表示されていることを確認
            foreach ($likedProducts as $product) {
                $response->assertSee($product->product_name); // 商品名が表示されていることを確認
                $response->assertSee($product->product_image); // 商品画像が表示されていることを確認
            }

            // いいねしていない商品が表示されていないことを確認
            $nonLikedProducts = Product::whereDoesntHave('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->select('product_name', 'product_image')
                ->get();

            foreach ($nonLikedProducts as $product) {
                $response->assertDontSee($product->product_name); // 商品名が表示されていないことを確認
                $response->assertDontSee($product->product_image); // 商品画像が表示されていないことを確認
            }
        }
    }

    public function test_sold_label_is_displayed_for_sold_products_in_my_list()
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

        // マイリストに移動
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

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

    public function test_logged_in_user_does_not_see_their_own_products_in_my_list()
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

        // マイリストに移動
        $response = $this->get('/?tab=mylist');

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

    public function testMyListAccessWithoutLogin()
    {
        // マイリストにアクセス
        $response = $this->get('/?tab=mylist');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // ページあたりのアイテム数を指定
        $perPage = 8;

        // ログインしている場合のみ表示されるべき商品情報が表示されていないことを確認
        for ($i = 0; $i < $perPage; $i++) {
            $response->assertDontSee('product_name_' . $i);
            $response->assertDontSee('product_image_' . $i);
        }
    }
}
