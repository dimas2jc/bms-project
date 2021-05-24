<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('ID_USER', 32)->primary();
            $table->string('username', 30);
            $table->string('password', 300);
            $table->string('NAMA', 50);
            $table->string('EMAIL', 100)->nullable();
            $table->string('NO_TELP', 13)->nullable();
            $table->tinyInteger('ROLE');
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
        Schema::dropIfExists('user');
    }
}
