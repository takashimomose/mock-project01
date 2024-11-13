<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PurchaseTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanPurchaseProduct()
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

        // Stripe APIをモック
        Http::fake([
            'api.stripe.com/*' => Http::response(['id' => 'pi_test_payment', 'status' => 'succeeded'], 200)
        ]);

        // 購入リクエストを送信（テスト環境での疑似的な決済完了）
        $response = $this->get(route('purchase.success', ['product_id' => $product->id]));

        // 購入完了後のリダイレクトとメッセージを確認
        $response->assertRedirect(route('index'));
        $response->assertSessionHas('message', '購入が完了しました。');

        // データベースで注文が作成されたことを確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'delivery_postal_code' => '123-4567',  // 適切な値に置き換える
            'delivery_address' => '東京都テスト区1-2-3',  // 適切な値に置き換える
            'delivery_building' => 'テストビル101号室'  // 適切な値に置き換える
        ]);

        // 商品のis_soldがtrueに更新されていることを確認
        $this->assertTrue($product->fresh()->is_sold);
    }
}
