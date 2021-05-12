<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeToCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->unsignedBigInteger('min_deposit')->default(0);
            $table->unsignedBigInteger('fee_withdraw')->default(0);
            $table->unsignedBigInteger('min_withdraw')->default(0);
            $table->boolean('maintenance')->default(0);
            $table->unsignedBigInteger('account_balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn(['fee_withdraw', 'min_deposit', 'min_withdraw', 'maintenance', 'account_balance']);
        });
    }
}
