<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiatDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiat_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('fiat_currency_id')->index();
            $table->unsignedDecimal('amount', 19, 2)->default(0);
            $table->text('description', 500)->nullable();
            $table->string('status', 10)->default('waiting');
            $table->text('note', 1000)->nullable();
            $table->unsignedInteger('evaluator_user_id')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('evaluated_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fiat_deposits');
    }
}
