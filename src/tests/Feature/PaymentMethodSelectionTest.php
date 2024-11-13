<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Hash;

class PaymentMethodSelectionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_payment_method_selection()
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

        $paymentMethod = PaymentMethod::create([
            'method_name' => 'カード払い',
        ]);

        // 支払い方法の選択ページにアクセス
        $response = $this->get(route('purchase', ['product_id' => $product->id]));

        // ページが正しく表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('支払い方法');

        // 支払い方法を選択してフォームを送信
        $response = $this->get(route('purchase', [
            'product_id' => $product->id,
            'payment_method_id' => $paymentMethod->id,
        ]));

        // 選択した支払い方法が表示されていることを確認
        $response->assertSeeText($paymentMethod->method_name);
    }
}
