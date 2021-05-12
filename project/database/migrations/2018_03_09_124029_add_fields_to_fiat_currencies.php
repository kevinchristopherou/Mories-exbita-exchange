<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFiatCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiat_currencies', function (Blueprint $table) {
            $table->string('bank_name', 255)->default('');
            $table->string('iban', 100)->default('');
            $table->string('recipient', 255)->default('');
            $table->string('swift_code', 255)->default('')->nullable();
            $table->unsignedBigInteger('withdraw_fee')->default(0);
            $table->unsignedBigInteger('withdraw_min')->default(0);
            $table->unsignedBigInteger('withdraw_max_daily')->default(0);
            $table->unsignedBigInteger('withdraw_max_monthly')->default(0);
            $table->unsignedBigInteger('deposit_min')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiat_currencies', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'iban',
                'recipient',
                'swift_code',
                'withdraw_fee',
                'withdraw_min',
                'withdraw_max_daily',
                'withdraw_max_monthly',
                'deposit_min',
            ]);
        });
    }
}
