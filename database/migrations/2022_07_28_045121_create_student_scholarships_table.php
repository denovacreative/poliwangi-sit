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
        Schema::create('siakad.student_scholarships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->double('amount');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->uuid('scholarship_id');
            $table->unsignedBigInteger('academic_period_id');
            $table->timestamps();
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.student_scholarships');
    }
};
