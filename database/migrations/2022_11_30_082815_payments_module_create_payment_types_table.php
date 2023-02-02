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
        Schema::create('payments.payment_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id');
            $table->string('name');
            $table->boolean('enabled');

            $table
                ->foreign('merchant_id')
                ->references('id')->on('payments.merchants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.payment_types');
    }
};
