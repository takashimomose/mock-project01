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
                    <!-- セッションに画像パスがある場合はプレビュー画像を表示 -->
                    <div class="image-preview">
                        @if (Session::has('profile_image_path'))
                            <img img id="preview" class="current-profile-image"
                                src="{{ asset('storage/' . Session::get('profile_image_path')) }}" alt="プロフィール画像">
                            <!-- ユーザーがアップロードした画像がある場合はその画像を表示 -->
                        @elseif (Auth::user()->profile_image)
                            <img img id="preview" class="current-profile-image"
                                src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                        @else
                            <!-- プロフィール画像がない場合にグレーの円を表示 -->
                            <img img id="preview" class="placeholder-profile-image">
                        @endif
                    </div>
                    <label for="profile-image" class="profile-image-upload">
                        画像を選択する
                    </label>
                    <input type="file" id="profile-image" name="profile_image" accept="image/*" style="display: none;"
                        onchange="previewImage(event)">
                </div>
                @error('profile_image')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- ユーザー名 -->
                <div class="form-group">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input class="form-input" type="text" name="name" placeholder=""
                        value="{{ old('name', $user->name) }}" />
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 郵便番号 -->
                <div class="form-group">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input class="form-input" type="text" name="postal_code" placeholder="例: 150-0000"
                        value="{{ old('postal_code', $user->postal_code) }}" inputmode="numeric"
                        maxlength="8">
                    @error('postal_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
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
            </form>
        </section>
    </main>
    {{-- 画像アップロード後のプレビュー表示 --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                const imagePreviewWrapper = document.querySelector('.image-preview');

                preview.src = reader.result;
                preview.style.display = 'block';
                imagePreviewWrapper.style.display = 'flex';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
