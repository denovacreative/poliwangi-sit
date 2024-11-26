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
        Schema::create('siakad.graduations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('student_id');
            $table->string('student_status_id');
            $table->uuid('study_program_id');
            $table->unsignedBigInteger('graduation_predicate_id')->nullable();
            $table->string('name');
            $table->date('graduation_date');
            $table->string('judiciary_number')->nullable();
            $table->date('judiciary_date')->nullable();
            $table->double('grade');
            $table->string('certificate_number')->nullable();
            $table->year('year')->nullable();
            $table->tinyText('description')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_status_id')->references('id')->on('student_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('graduation_predicate_id')->references('id')->on('graduation_predicates')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.graduations');
    }
};
