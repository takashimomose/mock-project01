<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'product_name' => '腕時計',
                'brand_name' => 'カシオ',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'HDD',
                'brand_name' => 'ソニー',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => '玉ねぎ3束',
                'brand_name' => '長野県農協',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' => 3,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => '革靴',
                'brand_name' => 'Gucci',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' => 4,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'ノートPC',
                'brand_name' => 'NEC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'マイク',
                'brand_name' => 'Audio Technica',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'ショルダーバッグ',
                'brand_name' => 'Adidas',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' => 3,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'タンブラー',
                'brand_name' => 'Starbucks',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' => 4,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'コーヒーミル',
                'brand_name' => 'Starbucks',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'メイクセット',
                'brand_name' => '花王',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'product_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
