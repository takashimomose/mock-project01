<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_email_is_required_for_login()
    {
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // メールアドレスを空にして他の必要項目を入力
        $response = $this->post('/login', [
            'email' => '', // メールアドレスを未入力
            'password' => 'password123',
        ]);

        // バリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required_for_login()
    {
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // パスワードを空にして他の必要項目を入力
        $response = $this->post('/login', [
            'email' => 'test@example.com', // メールアドレスを入力
            'password' => '', // パスワードを未入力
        ]);

        // バリデーションエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_unregistered_user_login_fails_with_error_message()
    {
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 未登録のユーザー情報を入力してログインを試みる
        $response = $this->post('/login', [
            'email' => 'unregistered@example.com', // 未登録のメールアドレス
            'password' => 'password123', // パスワード
        ]);

        // 「ログイン情報が登録されていません」というエラーメッセージが表示されることを確認
        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }
    
    public function test_redirect_to_profile_page_on_first_login()
    {
        // 初回ログインのユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'registered@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'last_login_at' => null, // 初回ログイン
        ]);
    
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);
    
        // ログインを試行
        $response = $this->post('/login', [
            'email' => 'registered@example.com',
            'password' => 'password123',
        ]);
    
        // 認証されていることを確認
        $this->assertAuthenticatedAs($user);
    
        // プロフィールページへのリダイレクトを確認
        $response->assertRedirect('/mypage/profile');
    
        // ユーザーの last_login_at が現在時刻で更新されていることを確認
        $this->assertNotNull($user->fresh()->last_login_at);
    }
    
    /** @test */
    public function test_redirect_to_mylist_on_subsequent_logins()
    {
        // 初回以降のログインユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'registered@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'last_login_at' => now(), // すでにログイン済み
        ]);
    
        // ログインページへのアクセス
        $response = $this->get('/login');
        $response->assertStatus(200);
    
        // // ログインを試行
        $response = $this->post('/login', [
            'email' => 'registered@example.com',
            'password' => 'password123',
        ]);
    
        // 認証されていることを確認
        $this->assertAuthenticatedAs($user);
    
        // // "/?tab=mylist" ページへのリダイレクトを確認
        $response->assertRedirect('/?tab=mylist');
    }
}
