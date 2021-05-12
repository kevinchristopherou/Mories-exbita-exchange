<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatsFieldsToMarkets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->decimal('volume_24h', 19, 8)->default(0);
            $table->decimal('low_24h', 19, 8)->default(0);
            $table->decimal('high_24h', 19, 8)->default(0);
            $table->decimal('bid', 19, 8)->default(0);
            $table->decimal('ask', 19, 8)->default(0);
            $table->decimal('last', 19, 8)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->dropColumn('volume_24h');
            $table->dropColumn('low_24h');
            $table->dropColumn('high_24h');
            $table->dropColumn('bid');
            $table->dropColumn('ask');
            $table->dropColumn('last');
        });
    }
}
