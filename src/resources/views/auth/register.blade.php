@extends('layouts.app')

@section('title', '会員登録') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>会員登録</h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <!-- お名前 -->
            <div class="form-group">
                <label for="name" class="form-label">お名前</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="例: 山田　太郎">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div class="form-group">
                <label for="email" class="form-label">メールアドレス</label>
                <input class="form-input" type="email" name="email"
                    value="{{ old('email') }}"placeholder="例: test@example.com">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- パスワード -->
            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input class="form-input" type="password" name="password" placeholder="例: coachtech1106">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- 確認用パスワード -->
            <div class="form-group">
                <label for="password-confirm">確認用パスワード</label>
                <input class="form-control" type="password" name="password_confirmation">
            </div>

            <!-- 登録するボタン -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    登録する
                </button>
            </div>

            <!-- ログインリンク -->
            <div class="form-group">
                <a href="{{ route('login') }}">ログインはこちら</a>
        </form>
    </section>
@endsection
