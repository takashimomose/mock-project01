<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name')->nullable(false);
            $table->string('brand_name')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->unsignedBigInteger('condition_id')->nullable(false);
            $table->boolean('is_sold')->default(false);
            $table->text('description')->nullable(false);
            $table->string('product_image', 255)->nullable(false);
            $table->timestamps();

            // 外部キー制約を追加、ON UPDATEおよびON DELETEをRESTRICTに設定
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('condition_id')
                ->references('id')
                ->on('conditions')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}