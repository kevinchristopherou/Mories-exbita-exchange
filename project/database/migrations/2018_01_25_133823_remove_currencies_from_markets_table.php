<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCurrenciesFromMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropColumn('market_currency');
            $table->dropColumn('market_currency_long');
            $table->dropColumn('base_currency');
            $table->dropColumn('base_currency_long');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->string('market_currency', 8);
            $table->string('market_currency_long', 30);
            $table->string('base_currency', 8);
            $table->string('base_currency_long', 30);
        });
    }
}
