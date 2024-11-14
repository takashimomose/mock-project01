<?php

return [
    'custom' => [
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
            'min' => '販売価格は0円以上で入力してください',
        ],
    ]
];
