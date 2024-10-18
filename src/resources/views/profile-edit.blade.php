@extends('layouts.app')

@section('title', 'プロフィール設定') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>プロフィール設定</h2>

        <form class="profile-edit" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- プロフィール画像 -->
            <div class="form-group">
                <input class="profile_image" type="file" name="profile_image" accept="image/*">

                <!-- アップロード済みの画像があれば表示 -->
                @if (Auth::user()->profile_image)
                    <div class="current-profile-image">
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像"
                            style="max-width: 100px; max-height: 100px;">
                    </div>
                @endif
            </div>

            <!-- ユーザー名 -->
            <div class="form-group">
                <label for="name" class="form-label">ユーザー名</label>
                <input class="name" type="text" name="name" placeholder="" value="{{ old('name', $user->name) }}" />
            </div>

            <!-- 郵便番号 -->
            <div class="form-group">
                <label for="postal_code" class="form-label">郵便番号</label>
                <input class="postal_code" type="number" name="postal_code" placeholder="例: 3998205"
                    value="{{ old('postal_code', $user->postal_code) }}" pattern="[0-9]*" inputmode="numeric"
                    maxlength="7">
            </div>

            <!-- 住所 -->
            <div class="form-group">
                <label for="address" class="form-label">住所</label>
                <input class="address" type="text" name="address" placeholder="例: 東京都渋谷区"
                    value="{{ old('address', $user->address) }}">
            </div>

            <!-- 建物名 -->
            <div class="form-group">
                <label for="building" class="form-label">建物名</label>
                <input class="building" type="text" name="building" placeholder="例: 渋谷ビル203号室"
                    value="{{ old('building', $user->building) }}">
            </div>

            <!-- 更新するボタン -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    更新する
                </button>
            </div>
    </section>
@endsection
