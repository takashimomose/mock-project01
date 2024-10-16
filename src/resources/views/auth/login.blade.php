@extends('layouts.app')

@section('title', 'ログイン') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>ログイン</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
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

            <!-- ログインボタン -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    ログインする
                </button>
            </div>

            <!-- 会員登録リンク -->
            <div class="form-group">
                <a href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </form>
    </section>
@endsection
