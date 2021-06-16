<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailCategoryCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_category_calendar', function (Blueprint $table) {
            $table->string('ID_OUTLET', 32)->index('fk1_detail_category_calendar');
            $table->string('ID_CATEGORY_CALENDAR', 32)->index('fk2_detail_category_calendar');
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
        Schema::dropIfExists('detail_category_calendar');
    }
}
