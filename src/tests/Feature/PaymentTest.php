<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Stripe::setApiKey(env('STRIPE_SECRET')); // Stripeのシークレットキーを設定
    }

    protected function createTestUser()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => 'test_user_image.jpg'
        ]);

        $this->actingAs($user);
        return $user;
    }

    protected function createTestCondition()
    {
        return Condition::create(['condition_name' => '新品']);
    }

    protected function createTestProduct($conditionId)
    {
        return Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $conditionId,
        ]);
    }

    protected function storeDeliveryAddressInSession()
    {
        session(['delivery_address_data' => [
            'postal_code' => '1234567',
            'address' => 'Tokyo, Japan',
            'building' => 'Test Building'
        ]]);
    }

    /** @test */
    public function it_redirects_to_stripe_checkout_for_konbini_payment()
    {
        $user = $this->createTestUser();
        $condition = $this->createTestCondition();
        $product = $this->createTestProduct($condition->id);
        $this->storeDeliveryAddressInSession();

        $requestData = ['payment_method_id' => 1]; // コンビニ決済
        $response = $this->post(route('checkout', ['product_id' => $product->id]), $requestData);

        $response->assertRedirect();
        $this->assertStringContainsString('https://checkout.stripe.com', $response->headers->get('Location'));
        $this->assertEquals(session('order_data')['product_id'], $product->id);
        $this->assertEquals(session('order_data')['user_id'], $user->id);
        $this->assertEquals(session('order_data')['delivery_address'], 'Tokyo, Japan');
    }

    /** @test */
    public function it_redirects_to_stripe_checkout_for_card_payment()
    {
        $user = $this->createTestUser();
        $condition = $this->createTestCondition();
        $product = $this->createTestProduct($condition->id);
        $this->storeDeliveryAddressInSession();

        $requestData = ['payment_method_id' => 2]; // クレジットカード決済
        $response = $this->post(route('checkout', ['product_id' => $product->id]), $requestData);

        $response->assertRedirect();
        $this->assertStringContainsString('https://checkout.stripe.com', $response->headers->get('Location'));
        $this->assertEquals(session('order_data')['product_id'], $product->id);
        $this->assertEquals(session('order_data')['user_id'], $user->id);
        $this->assertEquals(session('order_data')['delivery_address'], 'Tokyo, Japan');
    }

    /** @test */
    public function it_displays_error_for_invalid_payment_method_id()
    {
        $user = $this->createTestUser();
        $condition = $this->createTestCondition();
        $product = $this->createTestProduct($condition->id);

        $requestData = ['payment_method_id' => 99]; // 存在しない支払い方法
        $response = $this->post(route('checkout', ['product_id' => $product->id]), $requestData);

        $response->assertSessionHasErrors(['message' => '無効な支払い方法です。']);
    }
}
