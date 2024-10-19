@extends('layouts.app')

@section('title', '商品出品') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>商品の出品</h2>

        <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="product-image">商品画像</label>
                <input type="file" id="product-image" name="product_image" accept="image/*" onchange="previewImage(event)">
                @error('product_image')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div id="image-preview" style="margin-top: 10px;">
                    <img id="preview" src="#" alt="Image Preview" style="display: none; max-width: 200px;">
                </div>
            </div>

            <h3>商品の詳細</h3>
            <div class="form-group">
                <label for="condition">カテゴリー</label>
                <div class="category-buttons">
                    @foreach ($categories as $category)
                        <input type="checkbox" id="category_{{ $category->id }}" name="categories[]"
                            value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', $oldData['categories'] ?? [])) ? 'checked' : '' }}>
                        <label for="category_{{ $category->id }}" class="category-label">{{ $category->category_name }}</label>
                    @endforeach
                </div>
                @error('categories')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="condition">商品の状態</label>
                <select name="condition_id">
                    <option value="" hidden
                        {{ old('condition_id', $oldData['condition_id'] ?? '') ? '' : 'selected' }}>選択してください</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}"
                            {{ old('condition_id', $oldData['condition_id'] ?? '') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->condition_name }}
                        </option>
                    @endforeach
                </select>
                @error('condition_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <h3>商品名と説明</h3>
            <div class="form-group">
                <label for="product-name">商品名</label>
                <input type="text" name="product_name" placeholder=""
                    value="{{ old('product_name', $oldData['product_name'] ?? '') }}">
                @error('product_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">商品の説明</label>
                <textarea name="description" rows="4" placeholder="">{{ old('description', $oldData['description'] ?? '') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">販売価格</label>
                <input type="text" name="price" placeholder="¥" value="{{ old('price', $oldData['price'] ?? '') }}">
                @error('price')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">出品する</button>
            </div>
        </form>
    </section>

    {{-- 画像アップロード後のプレビュー表示 --}}
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            // 画像が選択された場合
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // プレビュー用の画像を設定
                    preview.style.display = 'block'; // プレビューを表示
                }
                reader.readAsDataURL(input.files[0]); // 画像ファイルを読み込む
            }
        }
    </script>
@endsection
