<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalancesToWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('available_balance', 12, 8)->default(0.00000000)->after('currency_id');
            $table->decimal('pending_balance', 12, 8)->default(0.00000000)->after('available_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('available_balance');
            $table->dropColumn('pending_balance');
        });
    }
}
