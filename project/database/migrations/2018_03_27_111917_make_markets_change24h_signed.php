<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMarketsChange24hSigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            $table->bigInteger('change_24h')->default(0)->change();
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
            \Illuminate\Support\Facades\DB::table('markets')->update([
               'change_24h' => 0,
            ]);
            $table->unsignedBigInteger('change_24h')->default(0)->change();
        });
    }
}
