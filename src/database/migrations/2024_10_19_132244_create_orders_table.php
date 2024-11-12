<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->unsignedBigInteger('user_id'); // 注文者ID（usersテーブルへの外部キー）
            $table->unsignedBigInteger('product_id'); // 商品ID（productsテーブルへの外部キー）
            $table->unsignedBigInteger('method_id'); // 支払い方法ID（payment_methodsテーブルへの外部キー）
            $table->string('delivery_postal_code'); // 配送先郵便番号
            $table->string('delivery_address'); // 配送先住所
            $table->string('delivery_building')->nullable(); // 配送先建物名（任意）
            $table->timestamp('order_date'); // 注文日時
            $table->timestamps(); // レコードの作成日時と更新日時

            // 外部キーの設定
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('method_id')->references('id')->on('payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
