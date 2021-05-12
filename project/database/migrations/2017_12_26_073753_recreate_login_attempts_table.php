<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateLoginAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('login_attempts');

        Schema::create('login_attempts', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('email', 50)->index();
            $table->string('ip', 50);
            $table->text('agent');
            $table->string('agent_hash', 192)->index();
            $table->unsignedInteger('client_id')->index()->default(0);
            $table->unsignedInteger('status')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_attempts');
    }
}
