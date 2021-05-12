<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxCoinpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_coinpayments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ipn_id', 100);
            $table->string('ipn_type', 100)->index();
            $table->string('deposit_id', 100);
            $table->string('txid', 66)->nullable()->index();
            $table->string('address', 255)->index();
            $table->string('dest_tag', 255)->nullable()->index();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('fee');
            $table->unsignedBigInteger('amount_without_fee');
            $table->string('amount_decimal', 255)->nullable();
            $table->integer('confirmations')->default(0);

            $table->unsignedBigInteger('main_balance_after')->default(0);
            $table->integer('wallet_address_id');
            $table->integer('wallet_id');
            $table->integer('user_id');

            $table->string('payment_status')->index();
            $table->string('payment_status_text');

            $table->boolean('ignore_deposit')->default(0);

            //status of the transaction, whether balance reflected to the owner user's balance or not.
            $table->string('app_status')->index();
            $table->timestamps();

            $table->unique([
                'txid'
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
        Schema::dropIfExists('tx_coinpayments');
    }
}
