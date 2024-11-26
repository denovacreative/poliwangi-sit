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
        Schema::create('siakad.thesis_examiners', function (Blueprint $table) {
            $table->id();
            $table->uuid('employee_id');
            $table->uuid('thesis_exam_schedule_id');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('thesis_exam_schedule_id')->references('id')->on('thesis_exam_schedules')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.thesis_examiners');
    }
};
