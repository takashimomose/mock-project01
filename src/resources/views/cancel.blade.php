@extends('layouts.app')

@section('title', '購入キャンセル') <!-- タイトルセクションを上書き -->

@push('css')
    <link rel="stylesheet" href="{{ asset('css/cancel.css') }}">
@endpush

@section('content')
    <main class="wrapper">
        <div class="container">
            <h1>購入がキャンセルされました</h1>
            <p><a href="{{ route('index') }}">商品一覧に戻る</a></p>
        </div>
    </main>
@endsection
