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
        Schema::table('user_addresses', function($table) {
            $table->unsignedBigInteger('address_type_id')->change();

            $table->foreign('address_type_id')
                ->references('id')->on('address_types')
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
        Schema::table('user_addresses', function($table) {
            $table->dropForeign('user_addresses_address_type_id_foreign');
            $table->integer('address_type_id')->change();
        });
    }
};
