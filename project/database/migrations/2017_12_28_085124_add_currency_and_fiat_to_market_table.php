<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyAndFiatToMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->index();
            $table->tinyInteger('currency_type')->unsigned()->index();
            $table->integer('currency_format_decimals')->unsigned();
            $table->integer('quote_currency_id')->unsigned()->index();
            $table->tinyInteger('quote_currency_type')->unsigned()->index();
            $table->integer('quote_currency_format_decimals')->unsigned();
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
            $table->dropColumn('currency_id');
            $table->dropColumn('currency_type');
            $table->dropColumn('quote_currency_id');
            $table->dropColumn('quote_currency_type');
            $table->dropColumn('currency_format_decimals');
            $table->dropColumn('quote_currency_format_decimals');
        });
    }
}
