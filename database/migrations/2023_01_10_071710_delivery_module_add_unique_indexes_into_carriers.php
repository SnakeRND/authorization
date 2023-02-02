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
        Schema::table('delivery.carriers', function (Blueprint $table) {
           $table->unique(['carrier_id','kladr'], 'carrier_id_uix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery.carriers', function (Blueprint $table) {
            $table->dropUnique('carrier_id_uix');
        });
    }
};
