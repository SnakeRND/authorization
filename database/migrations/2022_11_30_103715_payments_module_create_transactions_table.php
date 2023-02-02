<?php

use Illuminate\Database\Grammar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Grammar::macro('typeTransaction_status', function () {
            return 'payments.transaction_status';
        });

        DB::unprepared("CREATE TYPE payments.transaction_status AS ENUM ('pending', 'success', 'declined', 'failure', 'refund');");

        Schema::create('payments.transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid()->default(DB::raw('gen_random_uuid()'));
            $table->bigInteger('order_id');
            $table->bigInteger('payment_type_id')->nullable();
            $table->addColumn('transaction_status', 'status');
            $table->integer('amount');
            $table->integer('total');
            $table->boolean('fiscalized')->default(false);
            $table->bigInteger('gift_card_id')->nullable();
            $table->bigInteger('gift_card_code_id')->nullable();
            $table->dateTime('fiscalized_at')->nullable();
            $table->timestamps();

            $table
                ->foreign('payment_type_id')
                ->references('id')->on('payments.payment_types');

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
        Schema::dropIfExists('payments.transactions');
        DB::unprepared("DROP TYPE IF EXISTS payments.transaction_status");
    }
};
