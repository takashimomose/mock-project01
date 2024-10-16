@extends('layouts.app')

@section('title', 'プロフィール') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>プロフィール</h2>

            <!-- プロフィール画像 -->
            <div class="form-group">
                <label for="profile_image" class="form-label">プロフィール画像をアップロード</label>
                <input class="form-input" type="file" name="profile_image" accept="image/*">
                @error('profile_image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- ユーザー名 -->
            <div class="form-group">
                <label for="name" class="form-label">お名前</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="例: 山田　太郎">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

    </section>
@endsection
