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
        Schema::create('payments.transaction_redirect_urls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id');
            $table->string('redirect_url', 512);
            $table->timestamps();

            $table
                ->foreign('transaction_id')
                ->references('id')->on('payments.transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.transaction_redirect_urls');
    }
};
