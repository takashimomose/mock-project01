<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_logout_successfully()
    {
        // テスト用のユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'registered@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'last_login_at' => now(), // すでにログイン済み
        ]);

        // ユーザーをログインさせる
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト後のリダイレクト先を確認（ここではログインページを想定）
        $response->assertRedirect('/login');

        // ユーザーがログアウトされていることを確認
        $this->assertGuest();
    }
}
