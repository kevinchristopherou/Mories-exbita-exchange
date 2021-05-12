<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressPhotoIdToUserIdDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_id_documents', function (Blueprint $table) {
            $table->unsignedInteger('address_photo_id')->after('selfie_photo_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_id_documents', function (Blueprint $table) {
            $table->dropColumn('address_photo_id');
        });
    }
}
