<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIdDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_id_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('identity_photo_id')->nullable();
            $table->unsignedInteger('selfie_photo_id')->nullable();
            $table->string('ssid', 20);
            $table->string('id_type', 20)->nullable();
            $table->string('status', 10)->default('waiting');
            $table->unsignedInteger('evaluator_user_id')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('evaluated_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_id_documents');
    }
}
