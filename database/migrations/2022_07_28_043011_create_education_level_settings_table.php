<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siakad.education_level_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('study');
            $table->integer('max_leave');
            $table->integer('max_study');
            $table->unsignedBigInteger('education_level_id');
            $table->timestamps();
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.education_level_settings');
    }
};
