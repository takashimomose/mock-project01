<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Condition;
use App\Models\PaymentMethod;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mypage_displays_correct_data()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => 'test_user_image.jpg'
        ]);

        $this->actingAs($user); // ログイン処理

        // 商品の状態を作成
        $condition = Condition::create(['condition_name' => '新品']);

        // 出品した商品を作成
        $product1 = Product::create([
            'user_id' => $user->id,
            'product_name' => 'テスト商品1',
            'brand_name' => 'テストブランド',
            'price' => 1200,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
            'is_sold' => false, // 出品中
        ]);

        $product2 = Product::create([
            'user_id' => $user->id,
            'product_name' => 'テスト商品2',
            'brand_name' => 'テストブランド',
            'price' => 4500,
            'product_image' => 'test_image2.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
            'is_sold' => false, // 出品中
        ]);

        $product3 = Product::create([
            'user_id' => $user->id,
            'product_name' => 'テスト商品3',
            'brand_name' => 'テストブランド',
            'price' => 2000,
            'product_image' => 'test_image3.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
            'is_sold' => true, // 購入済み
        ]);

        $paymentMethod = PaymentMethod::create([
            'method_name' => 'カード払い',
        ]);

        // ユーザーが購入した商品をordersテーブルに追加
        Order::create([
            'user_id' => $user->id,
            'product_id' => $product3->id,
            'method_id' => 1,
            'delivery_postal_code' => '1234567',
            'delivery_address' => '東京都渋谷区テスト町1-2-3',
            'delivery_building' => 'テストビル101号室',
            'order_date' => now()
        ]);

        // /mypageにアクセス
        $response = $this->get('/mypage');

        // プロフィール画像、ユーザー名、出品中の商品、購入した商品が表示されていることを確認
        $response->assertStatus(200); // ステータスコード200が返されていること
        $response->assertSee($user->profile_image); // プロフィール画像
        $response->assertSee($user->name); // ユーザー名

        // 出品中の商品が表示されていること
        $response->assertSee($product1->product_name); // 商品名1
        $response->assertSee($product2->product_name); // 商品名2
        $response->assertSee($product1->product_image); // 商品画像1
        $response->assertSee($product2->product_image); // 商品画像2

        // 購入した商品が表示されていること
        $response->assertSee($product3->product_name); // 購入商品1
        $response->assertSee($product3->product_image); // 購入商品画像1
    }
}
