<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_fa_method', 20)->nullable()->default(null);
            $table->string('two_fa_secret', 255)->nullable();
            $table->boolean('two_fa_enabled')->default(0);
            $table->string('two_fa_otp', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_fa_method');
            $table->dropColumn('two_fa_secret');
            $table->dropColumn('two_fa_enabled');
            $table->dropColumn('two_fa_otp');
        });
    }
}
