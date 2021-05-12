<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainBalanceAfterToTxBtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tx_btc', function (Blueprint $table) {
            $table->unsignedBigInteger('main_balance_after')->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tx_btc', function (Blueprint $table) {
            $table->dropColumn('main_balance_after');
        });
    }
}
