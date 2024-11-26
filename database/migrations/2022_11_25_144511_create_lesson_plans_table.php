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
        Schema::create('siakad.lesson_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->text('study_program_outcome');
            $table->text('course_outcome');
            $table->text('course_description');
            $table->text('course_description_en');
            $table->text('learning_materials');
            $table->text('main_reference');
            $table->text('support_reference');
            $table->text('software_media');
            $table->text('hardware_media');
            $table->text('date');
            $table->boolean('attachment')->nullable();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.lesson_plans');
    }
};
