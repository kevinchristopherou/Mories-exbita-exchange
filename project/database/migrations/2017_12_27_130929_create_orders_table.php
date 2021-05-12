<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->primary('uuid');
            $table->integer('user_id')->unsigned()->index()->foreign()->references('id')->on('users')->onDelete('cascade');
            $table->integer('market_id')->unsigned()->index()->foreign()->references('id')->on('markets');
            $table->decimal('quantity', 12, 8)->default(0.00000000);
            $table->decimal('quantity_remaining', 12, 8)->default(0.00000000);
            $table->decimal('rate', 12, 8)->default(0.00000000);
            $table->string('type', 10);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
