<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('transactions');

        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->uuid('tx_uuid')->index();
            $table->uuid('order_uuid');
            $table->uuid('matched_order_uuid');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('market_id');
            $table->string('market');
            $table->string('type');
            $table->boolean('is_triggered')->default(false);
            $table->unsignedBigInteger('rate');
            $table->unsignedBigInteger('commission');
            $table->unsignedBigInteger('tax');
            $table->unsignedBigInteger('quote_amount');
            $table->unsignedBigInteger('crypto_amount');
            $table->timestamp('created_at');
            $table->index(['market', 'is_triggered', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
