<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletNoncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_nonces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 42)->index();
            $table->integer('nonce')->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->unique([
                'address',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_nonces');
    }
}
