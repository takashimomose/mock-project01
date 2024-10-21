@extends('layouts.app')

@section('title', '商品詳細') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <!-- product_image の表示方法を条件分岐で変更 -->
        @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
            <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}"
                style="max-width: 100px; max-height: 100px;">
        @else
            <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}"
                style="max-width: 100px; max-height: 100px;">
        @endif

        <div class="product-details">
            <h2>{{ $product->product_name }}</h2>
            <p class="brand-name">{{ $product->brand_name }}</p>
            <p class="price">¥{{ number_format($product->price) }}（税込）</p>
            <form action="{{ route('product.like', ['product_id' => $product->id]) }}" method="POST" class="like-form">
                @csrf
                <button type="submit" class="like-button">
                    <img src="{{ asset('images/likes.svg') }}" alt="likes">
                </button>
            </form>
            <p>{{ $likeCount }}</p>
            <a href="">
                <img src="{{ asset('images/comments.svg') }}" alt="comments">
            </a>
            <p>{{ $commentCount }}</p>
            <button class="proceed-purchase-btn"
                onclick="window.location='{{ route('purchase', ['product_id' => $product->id]) }}'">
                購入手続きへ
            </button>
        </div>

        <div class="product-description">
            <h3>商品説明</h3>
            <p>{{ $product->description }}</p>
        </div>

        <div class="product-info">
            <h3>商品の情報</h3>
            <!-- カテゴリーをループして表示 -->
            <label>カテゴリー</label>
            <p>
                @foreach ($product->categories as $category)
                    {{ $category->category_name }}
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </p>
            <label>商品の状態</label>
            <p>{{ $product->condition->condition_name }}</p>
        </div>

        <div class="comments-section">
            <h3>コメント ({{ $commentCount }})</h3>
            @foreach ($comments as $comment)
                <div class="comment">
                    <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="プロフィール画像"
                        style="max-width: 100px; max-height: 100px;">
                    <div class="comment-user">{{ $comment->user->name }}</div>
                    <div class="comment-text">{{ $comment->comment }}</div>
                </div>
            @endforeach
        </div>

        <div class="comment-form">
            <h3>商品へのコメント</h3>
            @auth
                <form action="{{ route('comment.store', ['product_id' => $product->id]) }}" method="POST">
                    @csrf
                    <textarea name="comment" rows="4" placeholder="コメントを入力してください">{{ old('comment', $oldData['comment'] ?? '') }}</textarea>
                    <button type="submit" class="submit-comment-btn">コメントを送信する</button>
                </form>
            @else
                <p>コメントを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
            @endauth
        </div>

    </section>
@endsection
