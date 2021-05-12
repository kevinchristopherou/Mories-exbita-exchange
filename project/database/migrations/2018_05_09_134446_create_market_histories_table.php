<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->timestamp('timestamp');
            $table->string('resolution');
            $table->integer('market_id')->nullable()->index();
            $table->unsignedBigInteger('o');
            $table->unsignedBigInteger('c');
            $table->unsignedBigInteger('h');
            $table->unsignedBigInteger('l');
            $table->unsignedBigInteger('v');
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
        Schema::dropIfExists('market_histories');
    }
}
