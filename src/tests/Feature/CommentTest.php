<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanPostCommentAndCommentCountIncreases()
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

        // 初期のコメント数を取得
        $initialCommentCount = Comment::where('product_id', $product->id)->count();

        // コメントデータ
        $commentData = [
            'comment' => 'テストコメント'
        ];

        // コメント投稿のリクエストを送信
        $response = $this->post(route('comment.store', ['product_id' => $product->id]), $commentData);

        // コメントが正しく保存されていることを確認
        $response->assertRedirect(route('product', ['product_id' => $product->id]));

        // 最新のコメント数を取得して確認
        $newCommentCount = Comment::where('product_id', $product->id)->count();
        $this->assertEquals($initialCommentCount + 1, $newCommentCount);
    }

    public function testGuestCannotPostComment()
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
            'condition_id' => $condition->id,
        ]);

        // コメントデータ
        $commentData = [
            'comment' => 'ログインしていない状態でのコメント'
        ];

        // コメント投稿のリクエストを送信（未ログイン状態）
        $response = $this->post(route('comment.store', ['product_id' => $product->id]), $commentData);

        // 未ログインのため、ログインページにリダイレクトされることを確認
        $response->assertRedirect(route('login'));

        // コメントが保存されていないことを確認
        $commentCount = Comment::where('product_id', $product->id)->count();
        $this->assertEquals(0, $commentCount);
    }

    public function testCommentValidationFailsForTooLongComment()
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

        // 256文字以上のコメントデータを作成
        $longComment = str_repeat('あ', 256); // 256文字のテスト用コメント
        $commentData = [
            'comment' => $longComment
        ];

        // コメント投稿のリクエストを送信
        $response = $this->post(route('comment.store', ['product_id' => $product->id]), $commentData);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);
    }

    public function testCommentValidationFailsForEmptyComment()
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

        // 空のコメントデータを用意
        $commentData = [
            'comment' => '' // 空のコメント
        ];

        // コメント投稿のリクエストを送信
        $response = $this->post(route('comment.store', ['product_id' => $product->id]), $commentData);

        // バリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }
}
