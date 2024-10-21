@extends('layouts.app')

@section('title', '商品購入') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Product Info -->
            <div class="product">
                <!-- product_image の表示方法を条件分岐で変更 -->
                @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                    <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}"
                        style="max-width: 100px; max-height: 100px;">
                @else
                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}"
                        style="max-width: 100px; max-height: 100px;">
                @endif
                <h1>{{ $product->product_name }}</h1>
                <p class="product-price">¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <hr>

        <!-- Payment Method -->
        <div class="section">
            <h2>支払い方法</h2>
            <select name="paymentMethod_id" id="paymentMethodSelect">
                <option value="" selected hidden>選択してください</option>
                @foreach ($paymentMethods as $paymentMethod)
                    <option value="{{ $paymentMethod->id }}"
                        {{ request('paymentMethod_id') == $paymentMethod->id ? 'selected' : '' }}>
                        {{ $paymentMethod->method_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Shipping Info -->
        <div class="section">
            <h2>配送先</h2>
            @if (session('delivery_address_data'))
                <!-- セッションにデータがある場合 -->
                <p>〒{{ substr(session('delivery_address_data')['postal_code'], 0, 3) }}-{{ substr(session('delivery_address_data')['postal_code'], 3) }}<br>
                    {{ session('delivery_address_data')['address'] }}{{ session('delivery_address_data')['building'] ? ' ' . session('delivery_address_data')['building'] : '' }}
                </p>
            @else
                <!-- セッションにデータがない場合 -->
                <p>〒{{ substr($user->postal_code, 0, 3) }}-{{ substr($user->postal_code, 3) }}<br>
                    {{ $user->address }}{{ $user->building ? ' ' . $user->building : '' }}
                </p>
            @endif
            <a href="{{ route('delivery-address.show', ['product_id' => $product->id]) }}" class="change-link">変更する</a>
        </div>
        </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <div class="summary">
                <div class="summary-item">
                    <h2>商品代金</h2>
                    <p>¥{{ number_format($product->price) }}</p>
                </div>
                <div class="summary-item">
                    <h2>支払い方法</h2>
                    <p id="selectedPaymentMethod">未選択</ｐ>
                    <h2>コンビニ払い</h2>
                </div>
            </div>
            <button class="purchase-button">購入する</button>
        </div>

        {{-- セッションが来ているかただの確認用 --}}
        @if (session('delivery_address_data'))
            <div>
                <h3>送付先住所:</h3>
                <p>郵便番号: {{ session('delivery_address_data')['postal_code'] }}</p>
                <p>住所: {{ session('delivery_address_data')['address'] }}</p>
                <p>建物名: {{ session('delivery_address_data')['building'] }}</p>
            </div>
        @endif
    </section>

    <script>
        // ドロップダウンの選択肢が変更されたときに選択された支払い方法を表示
        document.getElementById('paymentMethodSelect').addEventListener('change', function() {
            var selectedText = this.options[this.selectedIndex].text;
            document.getElementById('selectedPaymentMethod').textContent = selectedText;
        });
    </script>
@endsection
