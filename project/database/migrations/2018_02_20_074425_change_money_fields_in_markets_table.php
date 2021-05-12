<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMoneyFieldsInMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->unsignedBigInteger('volume_24h')->default(0)->change();
            $table->unsignedBigInteger('low_24h')->default(0)->change();
            $table->unsignedBigInteger('high_24h')->default(0)->change();
            $table->unsignedBigInteger('bid')->default(0)->change();
            $table->unsignedBigInteger('ask')->default(0)->change();
            $table->unsignedBigInteger('last')->default(0)->change();
            $table->unsignedBigInteger('change_24h')->default(0)->change();
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
            $table->decimal('volume_24h', 19, 8)->default(0)->change();
            $table->decimal('low_24h', 19, 8)->default(0)->change();
            $table->decimal('high_24h', 19, 8)->default(0)->change();
            $table->decimal('bid', 19, 8)->default(0)->change();
            $table->decimal('ask', 19, 8)->default(0)->change();
            $table->decimal('last', 19, 8)->default(0)->change();
            $table->decimal('change_24h', 19, 8)->default(0)->change();
        });
    }
}
