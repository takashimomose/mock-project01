@extends('layouts.app')

@section('title', '商品購入') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <section class="left-section">
            <div class="product">
                <!-- product_image の表示方法を条件分岐で変更 -->
                @if (filter_var($product->product_image, FILTER_VALIDATE_URL))
                    <img src="{{ $product->product_image }}" alt="{{ $product->product_name }}">
                @else
                    <img src="{{ Storage::url($product->product_image) }}" alt="{{ $product->product_name }}">
                @endif
                <div class="product-info">
                    <h1>{{ $product->product_name }}</h1>
                    <p class="product-price">¥ {{ number_format($product->price) }}</p>
                </div>
            </div>
            <!-- Payment Method -->
            <form method="POST" action="{{ route('checkout', ['product_id' => $product->id]) }}">
                @csrf
                <div class="payment-section">
                    <h2>支払い方法</h2>
                    <select name="payment_method_id" id="paymentMethodSelect">
                        <option value="" selected hidden>選択してください</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}"
                                {{ request('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                {{ $paymentMethod->method_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shipping Info -->
                <div class="delivery-address-section">
                    <div class="delivery-address-header">
                        <h2>配送先</h2>
                        <a href="{{ route('delivery-address.show', ['product_id' => $product->id]) }}"
                            class="change-link">変更する</a>
                    </div>
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
                </div>
        </section>
        <section class="right-section">
            <div class="summary">
                <div class="summary-item">
                    <h2>商品代金</h2>
                    <p>¥{{ number_format($product->price) }}</p>
                </div>
                <div class="summary-item">
                    <h2>支払い方法</h2>
                    <p id="selectedPaymentMethod">未選択</ｐ>
                </div>
            </div>
            <button class="purchase-btn" type="submit">購入する</button>
        </section>
        </form>
    </main>

    <script>
        // ドロップダウンの選択肢が変更されたときに選択された支払い方法を表示
        document.getElementById('paymentMethodSelect').addEventListener('change', function() {
            var selectedText = this.options[this.selectedIndex].text;
            document.getElementById('selectedPaymentMethod').textContent = selectedText;
        });
    </script>
@endsection
