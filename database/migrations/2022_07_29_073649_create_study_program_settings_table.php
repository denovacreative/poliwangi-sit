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
        Schema::create('siakad.study_program_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('study_program_id');
            $table->unsignedBigInteger('academic_period_id');
            $table->boolean('is_guardianship')->nullable();
            $table->boolean('is_open_heregistration')->nullable();
            $table->boolean('is_krs')->nullable();
            $table->boolean('is_khs')->nullable();
            $table->date('date_start_khs')->nullable();
            $table->date('date_end_khs')->nullable();
            $table->boolean('is_score')->nullable();
            $table->date('date_start_score')->nullable();
            $table->date('date_end_score')->nullable();
            $table->boolean('is_remedial_score')->nullable();
            $table->date('date_start_remedial_score')->nullable();
            $table->date('date_end_remedial_score')->nullable();
            $table->boolean('is_update_biodata')->nullable();
            $table->boolean('is_questionnaire')->nullable();
            $table->date('date_start_questionnaire')->nullable();
            $table->date('date_end_questionnaire')->nullable();
            // $table->boolean('is_lecture_generate');
            $table->integer('number_of_meeting')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.study_program_settings');
    }
};
