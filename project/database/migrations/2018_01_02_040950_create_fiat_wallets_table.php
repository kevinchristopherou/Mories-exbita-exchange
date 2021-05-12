<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiatWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiat_wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->foreign()->references('id')->on('users')->onDelete('cascade');
            $table->integer('fiat_currency_id')->unsigned()->index()->foreign()->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('available_balance', 12, 8)->default(0.00000000);
            $table->decimal('pending_balance', 12, 8)->default(0.00000000);
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
        Schema::dropIfExists('fiat_wallets');
    }
}
