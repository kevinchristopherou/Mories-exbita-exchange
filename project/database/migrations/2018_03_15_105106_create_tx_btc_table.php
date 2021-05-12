<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxBtcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_btc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('txid', 64);
            $table->string('blockhash', 64);
            $table->string('address', 36)->index();
            $table->string('category', 20);
            $table->unsignedBigInteger('amount');
            $table->integer('confirmations');
            $table->integer('blockindex');
            $table->integer('blocktime')->index();
            $table->integer('timereceived')->index();
            $table->string('label');
            $table->integer('vout');
            $table->integer('wallet_address_id');
            $table->integer('wallet_id');
            $table->integer('user_id');
            //status of the transaction, whether balance reflected to the owner user's balance or not.
            $table->string('app_status')->index();
            $table->timestamps();

            $table->unique([
                'txid',
                'blockhash'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tx_btc');
    }
}
