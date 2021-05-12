<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('currency_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedDecimal('amount', 19, 8)->default(0);
            $table->string('address', 255)->nullable();
            $table->string('status', 10)->default('waiting');
            $table->unsignedInteger('evaluator_user_id')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('evaluated_at', 0)->nullable();
        });

        Schema::create('fiat_withdrawal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fiat_currency_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedDecimal('amount', 19, 8)->default(0);
            $table->string('bank_name', 255)->nullable();
            $table->string('iban', 255)->nullable();
            $table->string('status', 10)->default('waiting');
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
        Schema::dropIfExists('withdrawal');
        Schema::dropIfExists('fiat_withdrawal');
    }
}
