<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWithdrawPendingBalanceToFiatWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiat_wallets', function (Blueprint $table) {
            $table->decimal('withdraw_pending_balance', 19, 8)->default(0)->after('pending_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiat_wallets', function (Blueprint $table) {
            $table->dropColumn('withdraw_pending_balance');
        });
    }
}
