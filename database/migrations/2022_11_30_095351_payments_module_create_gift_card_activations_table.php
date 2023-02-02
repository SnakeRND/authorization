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
        Schema::create('payments.gift_card_activations', function (Blueprint $table) {
            $table->bigInteger('gift_card_id')->nullable();
            $table->bigInteger('gift_card_code_id');
            $table->dateTime('sent_at');
            $table->dateTime('activated_at')->nullable();
            $table->timestamps();

            $table
                ->foreign('gift_card_id')
                ->references('id')->on('payments.gift_cards');

            $table
                ->foreign('gift_card_code_id')
                ->references('id')->on('payments.gift_card_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments.gift_card_activations');
    }
};
