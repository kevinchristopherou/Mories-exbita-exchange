<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRememberFieldToWithdrawTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal', function (Blueprint $table) {
            $table->string('remember_name')->nullable();
        });
        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->string('remember_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawal', function (Blueprint $table) {
            $table->dropColumn('remember_name');
        });
        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->dropColumn('remember_name');
        });
    }
}
