@extends('layouts.app')

@section('title', 'プロフィール') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="profile">
            <!-- プロフィール画像 -->
            <div class="profile-image-group">
                <!-- アップロード済みのプロフィール画像があれば表示 -->
                @if (Auth::user()->profile_image)
                    <img class = "current-profile-image" src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                        alt="プロフィール画像">
                @endif
                <h1>{{ $user->name }}</h1>
                <button class="edit-profile-btn"
                    onclick="window.location.href='{{ route('profile.edit') }}'">プロフィールを編集</button>
            </div>
        </section>

        <section class="tabs">
            <!-- タブボタンの選択 -->
            <input id="all" type="radio" name="tab-item" {{ $tab === 'sell' ? 'checked' : '' }}>
            <label class="tab-item" for="all" onclick="changeTab('all-products')">出品した商品</label>

            <input id="likes" type="radio" name="tab-item" {{ $tab === 'buy' ? 'checked' : '' }}>
            <label class="tab-item" for="likes" onclick="changeTab('mylist')">購入した商品</label>
        </section>
        <!-- 出品した商品タブ -->
        <section class="item">
            <div class="tab-content" id="all-content" style="{{ $tab === 'sell' ? 'display:block;' : 'display:none;' }}">
                <ul class="product-list">
                    @foreach ($products as $product)
                        <li>
                            <a href="{{ url('item/' . $product->id) }}">
                                <div class="image-container">
                                    @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                                        <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}">
                                    @else
                                        <img src="{{ Storage::url($product->product_image) }}"
                                            alt="{{ $product->product_name }}">
                                    @endif
                                </div>
                                <p>{{ $product->product_name }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="pagination">
                    {{ $products->links() }}
                </div>
            </div>
        </section>

        <!-- 購入した商品タブ -->
        <section class="item">
            <div class="tab-content" id="likes-content" style="{{ $tab === 'buy' ? 'display:block;' : 'display:none;' }}">
                @if (Auth::check())
                    <ul>
                        @foreach ($purchasedProducts as $product)
                            <li>
                                <a href="{{ url('item/' . $product->id) }}">
                                    @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                                        <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}"
                                            style="max-width: 100px; max-height: 100px;">
                                    @else
                                        <img src="{{ Storage::url($product->product_image) }}"
                                            alt="{{ $product->product_name }}"
                                            style="max-width: 100px; max-height: 100px;">
                                    @endif
                                    <h3>{{ $product->product_name }}</h3>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pagination">
                        {{ $purchasedProducts->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>

    <!-- タブ変更のためのJavaScript -->
    <script>
        function changeTab(tabName) {
            if (tabName === 'all-products') {
                // "出品した商品" タブがクリックされたら、/mypage?tab=sell にリダイレクト
                window.location.href = '/mypage?tab=sell';
            } else {
                // "購入した商品" タブがクリックされたら、/mypage?tab=buyにリダイレクト
                window.location.href = '/mypage?tab=buy';
            }
        }
    </script>
@endsection
