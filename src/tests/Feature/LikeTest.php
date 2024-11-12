<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;

class LikeTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanLikeProductAndLikeCountIncreases()
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

        // テスト用の商品を作成
        $product = Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
        ]);

        // 初期の「いいね」数
        $initialLikeCount = $product->likes()->count();

        // 直接「いいね」を追加（Likeモデルを使って）
        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        // 商品の「いいね」数が1増加していることを確認
        $product->refresh(); // 最新の状態をリフレッシュ
        $this->assertEquals($initialLikeCount + 1, $product->likes()->count());

        // 商品詳細ページにリダイレクトされることを確認
        $response = $this->get(route('product', ['product_id' => $product->id]));
        $response->assertStatus(200); // ページが正しく表示されることを確認

        // いいね数がページに表示されていることを確認
        $response->assertSee($product->likes()->count());
    }

    public function test_like_icon_changes_color_when_clicked()
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

        // テスト用の商品を作成
        $product = Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
        ]);

        // 商品詳細ページが正しく表示されるか確認
        $response = $this->get(route('product', ['product_id' => $product->id]));
        $response->assertStatus(200);

        // 3. いいねアイコンを押下
        // まず、まだ「いいね」をしていない状態なので、いいねをクリックして送信
        $this->post(route('product.like', ['product_id' => $product->id]));

        // ページがリダイレクトされることを確認
        $response = $this->get(route('product', ['product_id' => $product->id]));
        $response->assertStatus(200);

        // 4. いいねアイコンが黄色に変化しているか確認
        $response->assertSee('<img src="' . asset('images/likes-yellow.svg') . '" alt="likes">', false);
    }

    public function testUserCanUnlikeProductAndLikeCountDecreases()
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

        // テスト用の商品を作成
        $product = Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id,
        ]);

        // 初期の「いいね」数
        $initialLikeCount = $product->likes()->count();

        // 直接「いいね」を追加（Likeモデルを使って）
        $like = Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        // 商品の「いいね」数が1増加していることを確認
        $product->refresh(); // 最新の状態をリフレッシュ
        $this->assertEquals($initialLikeCount + 1, $product->likes()->count());

        // いいねを削除（「いいね」を取り消す）
        $like->delete();

        // 商品の「いいね」数が1減少していることを確認
        $product->refresh(); // 最新の状態をリフレッシュ
        $this->assertEquals($initialLikeCount, $product->likes()->count());

        // 商品詳細ページにリダイレクトされることを確認
        $response = $this->get(route('product', ['product_id' => $product->id]));
        $response->assertStatus(200); // ページが正しく表示されることを確認

        // いいね数がページに表示されていることを確認
        $response->assertSee($product->likes()->count());
    }
}
