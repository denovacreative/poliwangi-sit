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
        Schema::create('siakad.student_college_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('student_id');
            $table->string('student_status_id', 2);
            $table->unsignedBigInteger('finance_id')->nullable();
            $table->double('grade_semester')->default(0);
            $table->double('grade')->default(0);
            $table->double('credit_semester');
            $table->double('credit_total');
            $table->double('tuition_fee')->default(0)->nullable();
            $table->boolean('is_valid')->default(false);
            $table->string('decree_number')->nullable();
            $table->date('decree_date')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_status_id')->references('id')->on('student_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('finance_id')->references('id')->on('finances')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.student_college_activities');
    }
};
