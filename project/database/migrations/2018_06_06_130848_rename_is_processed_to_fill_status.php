<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIsProcessedToFillStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['market_id', 'type', 'updated_at', 'is_processed']);
            $table->dropColumn('is_processed');
            $table->unsignedTinyInteger('fill_status')->after('type')->default(0);
            $table->index(['market_id', 'type', 'fill_status']);
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
            $table->dropIndex(['market_id', 'type', 'fill_status']);
            $table->dropColumn('fill_status');
            $table->tinyInteger('is_processed')->after('type')->default(0);
            $table->index(['market_id', 'type', 'updated_at', 'is_processed']);
        });
    }
}
