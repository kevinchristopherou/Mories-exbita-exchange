<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBalanceFieldToFiatWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiat_wallets', function (Blueprint $table) {
            $table->decimal('available_balance', 19, 2)->default(0)->change();
            $table->decimal('pending_balance', 19, 2)->default(0)->change();
            $table->decimal('withdraw_pending_balance', 19, 2)->default(0)->change();
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
            $table->decimal('available_balance', 19, 8)->default(0)->change();
            $table->decimal('pending_balance', 19, 8)->default(0)->change();
            $table->decimal('withdraw_pending_balance', 19, 8)->default(0)->change();
        });
    }
}
