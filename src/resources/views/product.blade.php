@extends('layouts.app')

@section('title', '商品詳細') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="left-section">
            <div class="product-image">
                <!-- product_image の表示方法を条件分岐で変更 -->
                @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                    <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}">
                @else
                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}">
                @endif
            </div>
        </section>

        <section class="right-section">
            <div class="product-details">
                <h1>{{ $product->product_name }}</h1>
                <p class="brand-name">{{ $product->brand_name }}</p>
                <p class="price">¥{{ number_format($product->price) }}（税込）</p>
                <div class="like-comment-group">
                    <div class="like-count">
                        <form action="{{ route('product.like', ['product_id' => $product->id]) }}" method="POST"
                            class="like-form">
                            @csrf
                            <button type="submit" class="like-button">
                                @if ($isLiked)
                                    <!-- いいねしている場合は黄色い画像 -->
                                    <img src="{{ asset('images/likes-yellow.svg') }}" alt="likes">
                                @else
                                    <!-- いいねしていない場合は通常の画像 -->
                                    <img src="{{ asset('images/likes.svg') }}" alt="likes">
                                @endif
                        </form>
                        <p>{{ $likeCount }}</p>
                    </div>
                    <div class="comment-count">
                        <a href="">
                            <img src="{{ asset('images/comments.svg') }}" alt="comments">
                        </a>
                        <p>{{ $commentCount }}</p>
                    </div>
                </div>

                <button class="proceed-purchase-btn"
                    onclick="window.location='{{ route('purchase', ['product_id' => $product->id]) }}'">
                    購入手続きへ
                </button>
            </div>

            <div class="product-description">
                <h2>商品説明</h2>
                <p class="description">{{ $product->description }}</p>
            </div>

            <div class="product-info">
                <h2>商品の情報</h2>
                <!-- カテゴリーをループして表示 -->
                <div class="category-list">
                    <label class="product-label">カテゴリー</label>
                    <div class="category-items">
                        @foreach ($product->categories as $category)
                            <span class="category-item"> {{ $category->category_name }} </span>
                        @endforeach
                    </div>
                </div>
                <div class="condition-wrapper">
                    <label class="product-label">商品の状態</label>
                    <p class="condition">{{ $product->condition->condition_name }}</p>
                </div>
            </div>

            <div class="comment-section">
                <h2 class="comment-label">コメント ({{ $commentCount }})</h2>
                @foreach ($comments as $comment)
                    <div class="comment">
                        @if ($comment->user->profile_image)
                            <!-- プロフィール画像がある場合 -->
                            <img class="current-profile-image"
                                src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="プロフィール画像">
                        @else
                            <!-- プロフィール画像がない場合にグレーの円を表示 -->
                            <img class="placeholder-profile-image">
                        @endif
                        <p class="comment-user">{{ $comment->user->name }}</p>
                    </div>
                    <p class="comment-text">{{ $comment->comment }}</p>
                @endforeach
            </div>

            <div class="comment-form">
                <h3>商品へのコメント</h3>
                @auth
                    <form action="{{ route('comment.store', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        <textarea class="comment-input" name="comment" rows="4" placeholder="コメントを入力してください">{{ old('comment', $oldData['comment'] ?? '') }}</textarea>
                        @error('comment')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="submit-comment-btn">コメントを送信する</button>
                    </form>
                @else
                    <p>コメントを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
                @endauth
            </div>
        </section>
    </main>
@endsection
