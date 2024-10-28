@extends('layouts.app')

@section('title', 'プロフィール設定') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="tabs">
            <!-- タブボタンの選択 -->
            <input id="all" type="radio" name="tab-item" {{ request('tab') !== 'mylist' ? 'checked' : '' }}>
            <label class="tab-item" for="all" onclick="changeTab('all-products')">おすすめ</label>

            <input id="likes" type="radio" name="tab-item" {{ request('tab') === 'mylist' ? 'checked' : '' }}>
            <label class="tab-item" for="likes" onclick="changeTab('mylist')">マイリスト</label>
        </section>
        <!-- 商品一覧タブ -->
        <div class="tab-content" id="all-content"
            style="{{ request('tab') !== 'mylist' ? 'display:block;' : 'display:none;' }}">
            <ul class="product-list">
                @foreach ($products as $product)
                    <li>
                        <a href="{{ url('item/' . $product->id) }}">
                            <!-- product_image の表示方法を条件分岐で変更 -->
                            @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                                <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}">
                            @else
                                <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}">
                            @endif

                            <!-- 動的に product_name を表示 -->
                            <h3 class="product-name">{{ $product->product_name }}</h3>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class=pagination>
                {{ $products->links() }}
            </div>
        </div>

        <!-- いいねした商品一覧タブ -->
        <div class="tab-content" id="likes-content"
            style="{{ request('tab') === 'mylist' ? 'display:block;' : 'display:none;' }}">
            @if (Auth::check())
                <ul class="product-list">
                    @foreach ($likedProducts as $product)
                        <li>
                            <a href="{{ url('item/' . $product->id) }}">
                                @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                                    <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}">
                                @else
                                    <img src="{{ Storage::url($product->product_image) }}"
                                        alt="{{ $product->product_name }}">
                                @endif
                                <h3>{{ $product->product_name }}</h3>
                            </a>
                            <!-- is_soldがtrueの場合にSOLDを表示 -->
                            @if ($product->is_sold)
                                <span class="sold-label">SOLD</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <div class=pagination>
                    <!-- ページネーションリンクを表示 -->
                    {{ $likedProducts->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- タブ変更のためのJavaScript -->
    <script>
        function changeTab(tabName) {
            if (tabName === 'all-products') {
                // "おすすめ" タブがクリックされたら、クエリなしの / にリダイレクト
                window.location.href = '/';
            } else {
                // "マイリスト" タブがクリックされたら、?tab=mylist を付けてリダイレクト
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabName);
                url.searchParams.delete('page'); // ページ情報を削除
                window.location.href = url.href;
            }
        }
    </script>
@endsection
