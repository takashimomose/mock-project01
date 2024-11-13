<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class ChangeDeliveryAddressTest extends TestCase
{
    use DatabaseTransactions;

    public function test_change_delivery_address()
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

        // 配送先住所変更画面で住所を登録
        $addressData = [
            'postal_code' => '1234567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル101号室'
        ];

        $this->post("/purchase/address/{$product->id}", $addressData)
            ->assertRedirect("/purchase/{$product->id}");

        // 商品購入画面を再度開く
        $response = $this->get("/purchase/{$product->id}");

        // 郵便番号をフォーマットしてから期待結果を確認
        $formattedPostalCode = '〒' . substr($addressData['postal_code'], 0, 3) . '-' . substr($addressData['postal_code'], 3);

        // 期待結果：商品購入画面に登録した住所が反映されていることを確認
        $response->assertSeeText($formattedPostalCode);
        $response->assertSeeText($addressData['address']);
        $response->assertSeeText($addressData['building']);
    }
}
