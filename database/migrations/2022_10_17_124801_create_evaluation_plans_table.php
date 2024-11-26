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
        Schema::create('siakad.evaluation_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('evaluation_type_id');
            $table->uuid('course_id');
            $table->string('name');
            $table->double('weight');
            $table->string('description')->nullable();
            $table->string('description_en')->nullable();
            $table->timestamps();
            $table->foreign('evaluation_type_id')->references('id')->on('evaluation_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.evaluation_plans');
    }
};
