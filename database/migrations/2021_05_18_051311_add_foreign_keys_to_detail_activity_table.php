<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDetailActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_activity', function (Blueprint $table) {
            $table->foreign('ID_CATEGORY', 'detail_activity_ibfk_1')->references('ID_CATEGORY')->on('category_activity')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_activity', function (Blueprint $table) {
            $table->dropForeign('detail_activity_ibfk_1');
        });
    }
}
