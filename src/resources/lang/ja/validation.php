<?php

return [
    'custom' => [
        'name' => [
            'required' => 'ユーザー名を入力してください',
        ],
        'profile_image' => [
            'mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
        ],
        'postal_code' => [
            'regex' => '郵便番号はハイフンを含めて「3桁-4桁」の形式で入力してください',
        ],
        'product_image' => [
            'required' => '商品画像をアップロードしてください',
            'mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',
        ],
        'categories' => [
            'required' => 'カテゴリーを選択してください',
        ],
        'condition_id' => [
            'required' => '商品の状態を選択してください',
        ],
        'product_name' => [
            'required' => '商品名を入力してください',
        ],
        'description' => [
            'required' => '商品説明を入力してください',
        ],
        'price' => [
            'required' => '販売価格を入力してください',
            'numeric' => '販売価格は数値で入力してください',
            'min' => '販売価格は:min円以上で入力してください',
        ],
    ]
];
