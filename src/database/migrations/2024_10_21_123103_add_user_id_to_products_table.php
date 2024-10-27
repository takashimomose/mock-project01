<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // user_id カラムを追加（unsignedBigInteger）
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // 外部キー制約（users テーブルの id に関連）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['user_id']);

            // user_id カラムを削除
            $table->dropColumn('user_id');
        });
    }
}