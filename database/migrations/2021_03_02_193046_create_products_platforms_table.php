<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_platforms', function (Blueprint $table) {
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("platform_id");

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('platform_id')->references('id')->on('platforms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_platforms');
    }
}
