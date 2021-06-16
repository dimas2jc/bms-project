<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCategoryActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_activity', function (Blueprint $table) {
            $table->foreign('ID_OUTLET', 'category_activity_ibfk_1')->references('ID_OUTLET')->on('outlet')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_activity', function (Blueprint $table) {
            $table->dropForeign('category_activity_ibfk_1');
        });
    }
}
