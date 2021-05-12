<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryIndexesToOrdersAndTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['market_id', 'type', 'updated_at', 'is_processed']);
            $table->index(['market_id', 'type', 'rate']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['market_id', 'type', 'updated_at', 'is_processed']);
            $table->dropIndex(['market_id', 'type', 'rate']);
        });
    }
}
