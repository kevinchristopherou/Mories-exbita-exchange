<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tx_table_id');
            $table->string('txid', 255);
            $table->unsignedInteger('currency_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('wallet_id');
            $table->unsignedInteger('wallet_address_id');
            $table->integer('confirmations');
            $table->unsignedBigInteger('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_deposits');
    }
}
