<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_methods = [
            'コンビニ払い',
            'カード払い'
        ];

        foreach ($payment_methods as $payment_method) {
            DB::table('payment_methods')->insert([
                'method_name' => $payment_method,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
