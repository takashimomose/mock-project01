@extends('layouts.app')

@section('title', 'プロフィール設定') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="profile-edit">
            <h1>プロフィール設定</h1>

            <form class="profile-edit-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- プロフィール画像 -->
                <div class="profile-image-group">
                    <!-- アップロード済みの画像があれば表示 -->
                    @if (Auth::user()->profile_image)
                        <img class="current-profile-image" src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                            alt="プロフィール画像">
                    @endif
                    <label for="profile_image" class="profile-image-upload">
                        画像を選択する
                    </label>
                    <input id="profile_image" class="profile-image" type="file" name="profile_image" accept="image/*"
                        style="display: none;">
                </div>
                @error('profile_image')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- ユーザー名 -->
                <div class="form-group">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input class="form-input" type="text" name="name" placeholder=""
                        value="{{ old('name', $user->name) }}" />
                </div>

                <!-- 郵便番号 -->
                <div class="form-group">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input class="form-input" type="number" name="postal_code" placeholder="例: 3998205"
                        value="{{ old('postal_code', $user->postal_code) }}" pattern="[0-9]*" inputmode="numeric"
                        maxlength="7">
                </div>

                <!-- 住所 -->
                <div class="form-group">
                    <label for="address" class="form-label">住所</label>
                    <input class="form-input" type="text" name="address" placeholder="例: 東京都渋谷区"
                        value="{{ old('address', $user->address) }}">
                </div>

                <!-- 建物名 -->
                <div class="form-group">
                    <label for="building" class="form-label">建物名</label>
                    <input class="form-input" type="text" name="building" placeholder="例: 渋谷ビル203号室"
                        value="{{ old('building', $user->building) }}">
                </div>

                <!-- 更新するボタン -->
                <div class="form-group">
                    <button type="submit" class="primary-btn">
                        更新する
                    </button>
                </div>
        </section>
    </main>
@endsection
