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
        Schema::create('siakad.activity_score_conversions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('student_activity_member_id');
            $table->uuid('student_activity_id');
            $table->double('credit');
            $table->double('score');
            $table->string('grade', 3);
            $table->double('index_score');
            $table->boolean('is_transcript')->default(false);
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_activity_member_id')->references('id')->on('student_activity_members')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_activity_id')->references('id')->on('student_activities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.activity_score_conversions');
    }
};
