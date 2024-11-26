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
        Schema::create('siakad.teaching_lecturers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_substance_id')->nullable();
            $table->unsignedBigInteger('evaluation_type_id');
            $table->uuid('employee_id');
            $table->uuid('weekly_schedule_id')->nullable();
            $table->uuid('college_class_id');
            $table->double('credit_total');
            $table->double('credit_meeting');
            $table->double('credit_practicum');
            $table->double('credit_practice');
            $table->double('credit_simulation');
            $table->integer('meeting_plan');
            $table->integer('meeting_realization');
            $table->boolean('is_score_entry')->default(false);
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('lecture_substance_id')->references('id')->on('lecture_substances')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('evaluation_type_id')->references('id')->on('evaluation_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('weekly_schedule_id')->references('id')->on('weekly_schedules')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.teaching_lecturers');
    }
};
