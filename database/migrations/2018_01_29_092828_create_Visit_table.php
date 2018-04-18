<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('visitorid');
            $table->json('data');
            $table->enum('active', ['1', '2', '3'])->default('1');
            $table->enum('status', ['1', '2'])->default('1');
            $table->smallInteger('point')->nullable();
            $table->timestamps();
            $table->timestamp('closed_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit');
    }
}
