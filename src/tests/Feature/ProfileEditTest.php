<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ProfileEditTest extends TestCase
{
    use DatabaseTransactions;

    public function test_profile_edit_page_displays_initial_values()
    {
        // テスト用のユーザーを作成してログイン
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'profile_image' => 'test_user_image.jpg', // 初期のプロフィール画像
            'postal_code' => '3998205', // 初期の郵便番号
            'address' => '東京都渋谷区テスト町1-2-3', // 初期の住所
            'building' => 'テストビル101号室', // 初期の建物名
        ]);

        // ユーザーでログイン
        $this->actingAs($user); // ログイン処理

        // /mypage/profileにアクセス
        $response = $this->get('/mypage/profile');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // プロフィール画像が表示されていることを確認
        $response->assertSee('test_user_image.jpg');

        // ユーザー名が表示されていることを確認
        $response->assertSee('テストユーザー');

        // 郵便番号が表示されていることを確認
        $response->assertSee('3998205');

        // 住所が表示されていることを確認
        $response->assertSee('東京都渋谷区テスト町1-2-3');

        // 建物名が表示されていることを確認
        $response->assertSee('テストビル101号室');
    }
}
