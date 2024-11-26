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
        Schema::create('siakad.academic_periods', function (Blueprint $table) {
            $table->id();
            $table->year('academic_year_id');
            $table->enum('semester', ['1', '2', '3']);
            $table->string('name');
            $table->date('college_start_date');
            $table->date('college_end_date');
            $table->date('mid_exam_start_date')->nullable();
            $table->date('mid_exam_end_date')->nullable();
            $table->date('final_exam_start_date')->nullable();
            $table->date('final_exam_end_date')->nullable();
            $table->date('heregistration_start_date')->nullable();
            $table->date('heregistration_end_date')->nullable();
            $table->integer('number_of_meeting')->default(16);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_use')->default(false);
            $table->timestamps();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.academic_periods');
    }
};
