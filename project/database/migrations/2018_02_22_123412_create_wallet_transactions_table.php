<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wallet_address_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->string('type', 10)->default('waiting');
            $table->unsignedBigInteger('amount')->default(0);
            $table->unsignedBigInteger('fee')->default(0);
            $table->string('other_party_address', 200)->nullable();
            $table->string('tx_id', 200)->nullable();
            $table->string('status', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
}
