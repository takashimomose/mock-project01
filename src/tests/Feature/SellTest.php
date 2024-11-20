<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;

class SellTest extends TestCase
{
    use DatabaseTransactions;

    public function test_store_product_with_image_upload()
    {
        // ストレージのモック
        Storage::fake('public');

        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->actingAs($user);

        // 必要なデータを準備
        $condition = Condition::create(['condition_name' => '新品']);
        $category1 = Category::create(['category_name' => 'カテゴリー1']);
        $category2 = Category::create(['category_name' => 'カテゴリー2']);
        $categories = [$category1->id, $category2->id];

        // テスト用の画像ファイル
        $image = UploadedFile::fake()->image('product.jpg');

        // テストデータ
        $productData = [
            'product_image' => $image,
            'categories' => $categories,
            'condition_id' => $condition->id,
            'product_name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
        ];

        // POSTリクエストを送信
        $response = $this->post('/sell', $productData);

        // リダイレクト先の確認
        $response->assertRedirect(route('index'));

        // 画像が正しく保存されたことを確認
        Storage::disk('public')->assertExists('product_images/' . $image->hashName());

        // データベースに商品が保存されていることを確認
        $this->assertDatabaseHas('products', [
            'product_name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'product_image' => 'product_images/' . $image->hashName(),
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        // 商品を取得
        $product = Product::where('product_name', 'テスト商品')->first();

        // カテゴリを関連付ける
        $product->categories()->sync($categories);

        // カテゴリの関連付けが正しいことを確認
        $this->assertCount(count($categories), $product->categories);
        $this->assertTrue(collect($categories)->every(function ($id) use ($product) {
            return $product->categories->pluck('id')->contains($id);
        }));
    }
}
