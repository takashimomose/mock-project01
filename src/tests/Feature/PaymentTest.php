<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use Mockery;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_redirects_to_stripe_checkout_for_konbini_payment()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
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

        // 配送情報をセッションに保存
        Session::put('delivery_address_data', [
            'postal_code' => '123-4567',
            'address' => 'Test Address',
            'building' => 'Test Building'
        ]);

        // モック: Stripeセッションを作成する
        $mockSession = Mockery::mock(StripeSession::class);
        $mockSession->shouldReceive('create')
                    ->once() // 1回だけ呼ばれることを確認
                    ->andReturn((object) ['url' => 'https://checkout.stripe.com/test-url']);

        // モックしたStripeセッションをテスト中に使用
        $this->app->instance(StripeSession::class, $mockSession);

        // POSTリクエストを送信（コンビニ決済）
        $requestData = ['payment_method_id' => 1]; // コンビニ決済
        $response = $this->post(route('checkout', ['product_id' => $product->id]), $requestData);

        // リダイレクト先がStripeのチェックアウトURLであることを確認
        $response->assertRedirect();
        $this->assertStringContainsString('https://checkout.stripe.com/test-url', $response->headers->get('Location'));
    }

    /** @test */
    public function it_redirects_to_stripe_checkout_for_card_payment()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
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

        // 配送情報をセッションに保存
        Session::put('delivery_address_data', [
            'postal_code' => '123-4567',
            'address' => 'Test Address',
            'building' => 'Test Building'
        ]);

        // モック: Stripeセッションを作成する
        $mockSession = Mockery::mock(StripeSession::class);
        $mockSession->shouldReceive('create')
                    ->once() // 1回だけ呼ばれることを確認
                    ->andReturn((object) ['url' => 'https://checkout.stripe.com/test-url']);

        // モックしたStripeセッションをテスト中に使用
        $this->app->instance(StripeSession::class, $mockSession);

        // POSTリクエストを送信（クレジットカード決済）
        $requestData = ['payment_method_id' => 2]; // クレジットカード決済
        $response = $this->post(route('checkout', ['product_id' => $product->id]), $requestData);

        // リダイレクト先がStripeのチェックアウトURLであることを確認
        $response->assertRedirect();
        $this->assertStringContainsString('https://checkout.stripe.com/test-url', $response->headers->get('Location'));
    }
}
