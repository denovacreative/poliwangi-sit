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
        Schema::create('siakad.lesson_plan_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lesson_plan_id');
            $table->uuid('course_id');
            $table->integer('number_of_meeting');
            $table->text('sub_course_outcome');
            $table->text('learning_materials');
            $table->text('learning_method');
            $table->integer('duration');
            $table->text('learning_experience');
            $table->text('assesment_criteria_indicator');
            $table->double('weight');
            $table->timestamps();
            $table->foreign('lesson_plan_id')->references('id')->on('lesson_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.lesson_plan_details');
    }
};
