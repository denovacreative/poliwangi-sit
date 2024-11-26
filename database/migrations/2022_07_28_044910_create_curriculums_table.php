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
        Schema::create('siakad.curriculums', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('study_program_id');
            $table->unsignedBigInteger('academic_period_id');
            $table->string('name');
            $table->double('credit_total');
            $table->double('mandatory_credit');
            $table->double('choice_credit');
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
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
        Schema::dropIfExists('siakad.curriculums');
    }
};
