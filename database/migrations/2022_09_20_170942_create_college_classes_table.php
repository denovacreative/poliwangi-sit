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
        Schema::create('siakad.college_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('study_program_id');
            $table->uuid('curriculum_id')->nullable();
            $table->uuid('course_id');
            $table->unsignedBigInteger('lecture_system_id')->nullable();
            $table->string('name', 5);
            $table->integer('capacity')->default(0);
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('number_of_meeting')->default(16);
            $table->double('credit_total');
            $table->double('credit_meeting');
            $table->double('credit_practicum');
            $table->double('credit_practice');
            $table->double('credit_simulation');
            $table->text('case_discussion')->nullable();
            $table->boolean('is_lock_score')->default(false);
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('lecture_system_id')->references('id')->on('lecture_systems')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.college_classes');
    }
};
