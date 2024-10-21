@extends('layouts.app')

@section('title', '送付先住所変更') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/delivery-address.css') }}">
@endpush

@section('content')
    <section class="wrapper">
        <h2>住所の変更</h2>

        <form class="delivery-address-edit" action="{{ route('delivery-address.store', ['product_id' => $product_id]) }}"
            method="POST">
            @csrf
            {{-- 郵便番号 --}}
            <div class="form-group">
                <label for="postal_code" class="form-label">郵便番号</label>
                <input class="postal_code" type="number" name="postal_code" placeholder="例: 3998205"
                    value="{{ old('postal_code', session('delivery_address_data.postal_code', $user->postal_code)) }}"
                    pattern="[0-9]*" inputmode="numeric" maxlength="7">
                @error('postal_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- 住所 -->
            <div class="form-group">
                <label for="address" class="form-label">住所</label>
                <input class="address" type="text" name="address" placeholder="例: 東京都渋谷区"
                    value="{{ old('address', session('delivery_address_data.address', $user->address)) }}">
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- 建物名 -->
            <div class="form-group">
                <label for="building" class="form-label">建物名</label>
                <input class="building" type="text" name="building" placeholder="例: 渋谷ビル203号室"
                    value="{{ old('building', session('delivery_address_data.building', $user->building)) }}">
                @error('building')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- 更新するボタン -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    更新する
                </button>
            </div>
        </form>
    </section>
@endsection
