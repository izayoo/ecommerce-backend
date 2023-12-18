<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('slug');
            $table->integer('stock');
            $table->double('price', 8, 2);
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('media_id');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('product_category_id')
                ->references('id')->on('product_categories')
                ->onDelete('cascade');
            $table->foreign('media_id')
                ->references('id')->on('media')
                ->onDelete('cascade');
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
};
