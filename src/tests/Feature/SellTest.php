<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class SellTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sell_product()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // ログイン
        $this->actingAs($user);

        // ダミーのカテゴリと商品の状態を作成
        $category = Category::create(['category_name' => 'ファッション']);
        $condition = Condition::create(['condition_name' => '新品']);

        // 偽の画像ファイルを作成
        $image = UploadedFile::fake()->image('sample-image.jpg');

        // テスト用の商品を作成
        $product = Product::create([
            'condition_id' => $condition->id,
            'product_name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 1000,
            'product_image' => $image->hashName(), // 偽の画像ファイル名を保存
        ]);

        // 商品とカテゴリの紐付けを作成
        $product->categories()->attach($category->id);

        // フォームデータを準備
        $formData = [
            'condition_id' => $condition->id,
            'product_name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 1000,
            'product_image' => $image, // 画像をフォームデータに追加
            'categories' => [$category->id],
        ];

        // /sellにPOSTリクエストを送信
        $response = $this->post('/sell', $formData);

        // ステータスコードが302 (リダイレクト) であることを確認
        $response->assertStatus(302);

        // DBに保存された商品を検証
        $this->assertDatabaseHas('products', [
            'product_name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 1000,
        ]);

        // 商品とカテゴリの紐付けが正しく行われていることを確認
        $this->assertDatabaseHas('category_product', [
            'product_id' => $product->id,
            'category_id' => $category->id,
        ]);
    }
}
