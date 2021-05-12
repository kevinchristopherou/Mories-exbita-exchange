<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            //drop columns
            $table->dropColumn(['status', 'deleted_at', 'updated_at', 'tx_id']);
            //new columns
            $table->unsignedInteger('currency_id')->index()->after('user_id');
            $table->string('txid', 255)->nullable()->after('id');
            //changes
            $table->unsignedInteger('wallet_address_id')->nullable()->change();
            //add indexes
            $table->index(['user_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            //drop added columns
            $table->dropColumn('currency_id');
            $table->dropColumn('txid');
            //drop added indexes
            $table->dropIndex(['user_id', 'currency_id']);
            //revert column changes
            $table->unsignedInteger('wallet_address_id')->change();
            //re-add columns
            $table->string('status')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('tx_id', 200)->nullable();
        });
    }
}
