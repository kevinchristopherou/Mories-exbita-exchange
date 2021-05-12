<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentToWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal', function (Blueprint $table) {
            $table->text('note', 1000)->after('status')->nullable();
        });

        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->text('note', 1000)->after('status')->nullable();
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
            $table->dropColumn('note');
        });

        Schema::table('fiat_withdrawal', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}
