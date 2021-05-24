<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->string('ID_PROGRESS', 32)->primary();
            $table->string('ID_DETAIL_ACTIVITY', 32)->index('MEMILIKI3_FK');
            $table->string('PROGRESS', 10);
            $table->string('KETERANGAN', 300)->nullable();
            $table->string('FILE', 100)->nullable();
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
        Schema::dropIfExists('progress');
    }
}
