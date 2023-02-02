<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments.payment_type_merchant_config_parameters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_type_id');
            $table->string('name');
            $table->string('value');

            $table->unique(['payment_type_id', 'name'], 'payments_ptmcp_ptid_name_ukey');

            $table
                ->foreign('payment_type_id', 'payments_ptmcp_ptid_payments_ptid_fkey')
                ->references('id')->on('payments.payment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.payment_type_merchant_config_parameters');
    }
};
