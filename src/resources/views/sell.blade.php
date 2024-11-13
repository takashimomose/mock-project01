@extends('layouts.app')

@section('title', '商品出品') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="product-sell">
            <h1>商品の出品</h1>

            <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="product-image-label" class="form-label">商品画像</label>
                <div class="product-image-group">
                    <div class="product-image-wrapper">
                        <div class="image-preview">
                            <img id="preview" src="#" alt="Image Preview" style="display: none;">
                        </div>
                        <label for="product-image" class="product-image-upload">
                            画像を選択する
                        </label>
                        <input type="file" id="product-image" name="product_image" accept="image/*"
                            style="display: none;" onchange="previewImage(event)">
                    </div>
                </div>
                @error('product_image')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <h2>商品の詳細</h2>
                <div class="form-group">
                    <label for="condition" class="form-label">カテゴリー</label>
                    <div class="category-buttons">
                        @foreach ($categories as $category)
                            <input type="checkbox" id="category_{{ $category->id }}" name="categories[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', $oldData['categories'] ?? [])) ? 'checked' : '' }}>
                            <label for="category_{{ $category->id }}"
                                class="category-label">{{ $category->category_name }}</label>
                        @endforeach
                    </div>
                    @error('categories')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="condition" class="form-label">商品の状態</label>
                    <select class="form-input" name="condition_id">
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

                <h2>商品名と説明</h2>
                <div class="form-group">
                    <label for="product-name" class="form-label">商品名</label>
                    <input class="form-input" type="text" name="product_name" placeholder=""
                        value="{{ old('product_name', $oldData['product_name'] ?? '') }}">
                    @error('product_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">商品の説明</label>
                    <textarea class="form-input" name="description" rows="4" placeholder="">{{ old('description', $oldData['description'] ?? '') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">販売価格</label>
                    <input class="form-input" type="text" name="price" placeholder="¥"
                        value="{{ old('price', $oldData['price'] ?? '') }}">
                    @error('price')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="primary-btn">出品する</button>
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
                imagePreviewWrapper.style.display = 'flex'; // アップロード後に表示
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
