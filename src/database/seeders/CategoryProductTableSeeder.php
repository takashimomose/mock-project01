<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductTableSeeder extends Seeder
{
    public function run()
    {
        // カテゴリと商品を中間テーブルに挿入
        DB::table('category_product')->insert([
            ['product_id' => 1, 'category_id' => 1],
            ['product_id' => 2, 'category_id' => 2],
            ['product_id' => 3, 'category_id' => 10],
            ['product_id' => 4, 'category_id' => 5],
            ['product_id' => 5, 'category_id' => 2],
            ['product_id' => 6, 'category_id' => 13],
            ['product_id' => 7, 'category_id' => 1],
            ['product_id' => 8, 'category_id' => 10],
            ['product_id' => 9, 'category_id' => 2],
            ['product_id' => 10, 'category_id' => 6],
            ['product_id' => 1, 'category_id' => 7],
            ['product_id' => 1, 'category_id' => 5],
            ['product_id' => 1, 'category_id' => 8],
            ['product_id' => 1, 'category_id' => 9],
            ['product_id' => 1, 'category_id' => 10],
            ['product_id' => 1, 'category_id' => 3],
        ]);
    }
}