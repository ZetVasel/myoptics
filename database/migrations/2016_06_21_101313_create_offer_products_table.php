<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dv_offer_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offer_id' )->unsigned();
            $table->integer('product_id' )->unsigned();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('dv_products')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('dv_offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dv_offer_products');
    }
}
