<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFiatWithdrawalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->renameColumn('bank_account', 'recipient');
            $table->string('swift_code', 20)->after('iban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->renameColumn('recipient', 'bank_account');
            $table->dropColumn('swift_code');
        });
    }
}
