@extends('layouts.app')

@section('title', '商品詳細') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <a href="">
            <img src="" alt=""> {{-- ここ商品画像を表示  --}}
        </a>

        <div class="product-details">
            <h2>{{ $product->product_name }}</h2>
            <p class="brand-name">{{ $product->brand_name }}</p>
            <p class="price">￥{{ number_format($product->price) }}（税込）</p>
            <a href="">
                <img src="{{ asset('images/likes.svg') }}" alt="likes">
            </a>
            {{-- ここにいいね数を表示  --}}
            <a href="">
                <img src="{{ asset('images/comments.svg') }}" alt="comments">
            </a>
            {{-- ここにコメント数を表示  --}}
            <button class="proceed-purchase-btn">購入手続きへ</button>
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
            <h3>コメント</h3>
            <div class="comment">
                <div class="comment-user">admin</div>
                <div class="comment-text">こちらにコメントが入ります。</div>
            </div>
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
