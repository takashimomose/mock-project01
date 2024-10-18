<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $likes = [
            [
                'user_id' => 1,
                'product_id' => 2
            ],
            [
                'user_id' => 1,
                'product_id' => 4
            ],
            [
                'user_id' => 1,
                'product_id' => 5
            ],
        ];

        foreach ($likes as $like) {
            DB::table('likes')->insert([
                'user_id' => $like['user_id'],
                'product_id' => $like['product_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
