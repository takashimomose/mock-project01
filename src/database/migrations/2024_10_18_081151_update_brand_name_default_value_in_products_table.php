<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBrandNameDefaultValueInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // brand_nameカラムのデフォルト値をnullに変更
            $table->string('brand_name')->nullable()->default(null)->change();
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
            // デフォルト値を元に戻す（必要に応じて設定）
            $table->string('brand_name')->nullable(false)->change(); // 必要に応じて元に戻す
        });
    }
}
