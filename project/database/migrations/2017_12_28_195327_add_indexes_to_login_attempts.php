<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToLoginAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->index([
               'email', 'created_at',
            ]);

            $table->index([
                'ip', 'created_at',
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
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->dropIndex([
                'email', 'created_at',
            ]);

            $table->dropIndex([
                'ip', 'created_at',
            ]);
        });
    }
}
