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
        Schema::table('order_products', function ($table) {
            $table->unsignedBigInteger('campaign_id');
            $table->integer('quantity');
            $table->boolean('is_for_donation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function ($table) {
            $table->dropColumn('campaign_id');
            $table->dropColumn('quantity');
            $table->dropColumn('is_for_donation');
        });
    }
};
