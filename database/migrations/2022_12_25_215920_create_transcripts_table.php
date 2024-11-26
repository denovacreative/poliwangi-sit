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
        Schema::create('siakad.transcripts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->uuid('score_id')->nullable();
            $table->uuid('college_class_id')->nullable();
            $table->uuid('score_transfer_id')->nullable();
            $table->uuid('activity_score_conversion_id')->nullable();
            $table->double('credit');
            $table->integer('semester');
            $table->double('score');
            $table->string('grade', 3);
            $table->double('index_score');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('score_id')->references('id')->on('scores')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('score_transfer_id')->references('id')->on('score_transfers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('activity_score_conversion_id')->references('id')->on('activity_score_conversions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.transcripts');
    }
};
