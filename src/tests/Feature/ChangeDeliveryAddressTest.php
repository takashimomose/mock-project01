<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
            'profile_image' => 'test_user_image.jpg',
            'postal_code' => null, // 初期値はnull
            'address' => null,     // 初期値はnull
            'building' => null,    // 初期値はnull
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

        // 送付先住所データ
        $addressData = [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町1-2-3',
            'building' => 'テストビル101号室',
        ];

        // POSTリクエストを送信
        $response = $this->post("/purchase/address/{$product->id}", $addressData);

        // 1. リダイレクト先の確認
        $response->assertRedirect(route('purchase', ['product_id' => $product->id]));

        // 2. セッションに保存されているデータの確認
        $this->assertEquals($addressData, Session::get('delivery_address_data'));

        // 3. ユーザー情報が更新されたかの確認
        $user->refresh(); // モデルを再取得して変更を確認
        $this->assertEquals('123-4567', $user->postal_code);
        $this->assertEquals('東京都渋谷区テスト町1-2-3', $user->address);
        $this->assertEquals('テストビル101号室', $user->building);

        // 4. 商品購入ページが正しく開けることを確認
        $response = $this->get("/purchase/{$product->id}");
        $response->assertStatus(200);
    }
}
