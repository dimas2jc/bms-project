<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDetailCategoryCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_category_calendar', function (Blueprint $table) {
            $table->foreign('ID_CATEGORY_CALENDAR', 'detail_category_calendar_ibfk_1')->references('ID_CATEGORY_CALENDAR')->on('category_calendar')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_category_calendar', function (Blueprint $table) {
            $table->dropForeign('detail_category_calendar_ibfk_1');
        });
    }
}
