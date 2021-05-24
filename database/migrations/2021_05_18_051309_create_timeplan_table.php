<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeplanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeplan', function (Blueprint $table) {
            $table->string('ID_TIMEPLAN', 32)->primary();
            $table->string('ID_DETAIL_ACTIVITY', 32)->index('MEMILIKI2_FK');
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
        Schema::dropIfExists('timeplan');
    }
}
