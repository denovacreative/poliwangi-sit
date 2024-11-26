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
        Schema::create('siakad.judicial_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('judicial_period_id');
            $table->date('decree_date')->nullable();
            $table->string('decree_number')->nullable();
            $table->date('certificate_date')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('transcript_date')->nullable();
            $table->string('transcript_number')->nullable();
            $table->string('national_certificate_number')->nullable();
            $table->string('nirl')->nullable();
            $table->integer('semester');
            $table->double('credit');
            $table->double('grade');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('judicial_period_id')->references('id')->on('judicial_periods')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.judicial_participants');
    }
};
