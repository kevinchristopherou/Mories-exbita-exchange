<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // todo: link to currencies with id
        Schema::create('markets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('market_currency', 8);
            $table->string('market_currency_long', 30);
            $table->string('base_currency', 8);
            $table->string('base_currency_long', 30);
            $table->boolean('is_active')->default(1);
            $table->decimal('min_trade_size', 12, 8)->default(0.00000000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markets');
    }
}
