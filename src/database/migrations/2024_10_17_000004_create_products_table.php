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
            $table->id();
            $table->string('product_name');
            $table->string('brand_name')->nullable();
            $table->integer('price');
            $table->unsignedBigInteger('condition_id');
            $table->boolean('is_sold')->default(false);
            $table->text('description');
            $table->string('product_image', 255);
            $table->timestamps();

            // 外部キー制約を追加、ON UPDATEおよびON DELETEをRESTRICTに設定
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
