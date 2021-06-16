<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_activity', function (Blueprint $table) {
            $table->string('ID_DETAIL_ACTIVITY', 32)->primary();
            $table->string('ID_CATEGORY', 32)->index('MEMILIKI1_FK');
            $table->string('NAMA_AKTIFITAS', 100);
            $table->tinyInteger('STATUS');
            $table->tinyInteger('FLAG')->nullable();
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
        Schema::dropIfExists('detail_activity');
    }
}
