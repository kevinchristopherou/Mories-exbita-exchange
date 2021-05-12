<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxErcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_erc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('txid', 66);
            $table->string('blockhash', 66)->nullable();
            $table->string('address', 42)->index();
            $table->string('contract_address', 42)->index();
            $table->unsignedBigInteger('amount');
            $table->string('amount_in_gwei', 35);
            $table->string('category', 10)->nullable();
            $table->integer('confirmations')->default(0);
            $table->integer('blockindex')->nullable();
            $table->integer('blocktime')->nullable()->index();
            $table->integer('timereceived')->index()->nullable();

            $table->integer('gas')->nullable();
            $table->string('gas_price', 30)->nullable();

            $table->longText('input')->nullable();
            $table->integer('nonce')->nullable();
            $table->integer('transactionindex')->nullable();

            $table->string('s', 255)->nullable();
            $table->string('r', 255)->nullable();
            $table->string('v', 255)->nullable();

            $table->string('txid_main_wallet', 66)->nullable();
            $table->unsignedBigInteger('main_balance_after')->default(0);
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
        Schema::dropIfExists('tx_erc');
    }
}
