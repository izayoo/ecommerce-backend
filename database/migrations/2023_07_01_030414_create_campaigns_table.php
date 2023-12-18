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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->unsignedBigInteger('product_id');
            $table->integer('max_tickets')->default(0);
            $table->unsignedBigInteger('campaign_category_id');
            $table->dateTime('draw_date');
            $table->unsignedBigInteger('media_id');
            $table->integer('status');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            $table->foreign('campaign_category_id')
                ->references('id')->on('campaign_categories')
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
        Schema::dropIfExists('campaigns');
    }
};
