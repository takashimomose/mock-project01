<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    public function testProductDetailPageDisplaysAllRequiredElements()
    {
        // 商品の状態を作成
        $condition = Condition::create(['condition_name' => '新品']);

        // テスト用の商品を作成
        $product = Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id, // condition_idを設定
        ]);

        // テスト用のカテゴリを作成し商品に関連付け
        $category = Category::create(['category_name' => 'Test Category']);
        $product->categories()->attach($category->id);

        // 商品の状態を作成し関連付け
        $product->condition()->associate($condition);
        $product->save();

        // テスト用のユーザーとコメントを作成し、商品に関連付け
        $user = User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => Hash::make('password123'), 'profile_image' => 'test_user_image.jpg']);
        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'comment' => 'テストコメント',
        ]);

        // いいね数とコメント数を設定
        $likeCount = 5;
        $commentCount = 1;

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$product->id}");

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品画像が表示されていることを確認
        if (filter_var($product->product_image, FILTER_VALIDATE_URL)) {
            $response->assertSee('<img src="' . $product->product_image . '"', false);
        } else {
            $response->assertSee('<img src="' . Storage::url($product->product_image) . '"', false);
        }

        // 商品名、ブランド名、価格が表示されていることを確認
        $response->assertSee($product->product_name);
        $response->assertSee($product->brand_name);
        $response->assertSee('¥' . number_format($product->price));

        // いいね数とコメント数が表示されていることを確認
        $response->assertSee($likeCount);
        $response->assertSee($commentCount);

        // 商品説明が表示されていることを確認
        $response->assertSee($product->description);

        // 商品情報（カテゴリ、商品の状態）が表示されていることを確認
        $response->assertSee('カテゴリー');
        $response->assertSee($category->category_name);
        $response->assertSee('商品の状態');
        $response->assertSee($condition->condition_name);

        // コメント数、コメントしたユーザー情報、コメント内容が表示されていることを確認
        $response->assertSee('コメント (' . $commentCount . ')');
        $response->assertSee($user->name);

        // プロフィール画像が表示されていることを確認
        $response->assertSee('<img class="current-profile-image" src="' . asset('storage/' . $user->profile_image) . '"', false);

        // コメント内容が表示されていることを確認
        $response->assertSee($comment->comment);
    }

    public function testProductDetailPageDisplaysMultipleCategories()
    {
        // 商品の状態を作成
        $condition = Condition::create(['condition_name' => '新品']);

        // テスト用の商品を作成
        $product = Product::create([
            'product_name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 12345,
            'product_image' => 'test_image.jpg',
            'description' => 'テスト商品説明',
            'condition_id' => $condition->id, // condition_idを設定
        ]);

        // 複数のカテゴリを作成
        $category1 = Category::create(['category_name' => 'テストカテゴリ 1']);
        $category2 = Category::create(['category_name' => 'テストカテゴリ 2']);
        $category3 = Category::create(['category_name' => 'テストカテゴリ 3']);

        // 商品に複数のカテゴリを関連付け
        $product->categories()->attach([$category1->id, $category2->id, $category3->id]);

        // 商品の状態を作成し関連付け
        $product->condition()->associate($condition);
        $product->save();

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$product->id}");

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 商品に関連付けられたカテゴリが表示されていることを確認
        $response->assertSee($category1->category_name);
        $response->assertSee($category2->category_name);
        $response->assertSee($category3->category_name);
    }
}
