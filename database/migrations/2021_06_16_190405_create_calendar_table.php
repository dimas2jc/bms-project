<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->string('ID_CALENDAR', 32)->primary();
            $table->string('ID_CATEGORY_CALENDAR', 32)->index('fk_calendar');
            $table->string('JUDUL', 100);
            $table->text('DESKRIPSI');
            $table->date('TANGGAL_START');
            $table->date('TANGGAL_END');
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
        Schema::dropIfExists('calendar');
    }
}
