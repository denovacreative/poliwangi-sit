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
        Schema::create('siakad.scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id')->nullable();
            $table->uuid('college_class_id');
            $table->uuid('student_id');
            $table->double('mid_exam')->nullable();
            $table->double('final_exam')->nullable();
            $table->double('coursework')->nullable();
            $table->double('quiz')->nullable();
            $table->double('attendance')->nullable();
            $table->double('practice')->nullable();
            $table->double('final_score')->nullable(); // NA Feeder
            $table->double('remedial_score')->nullable(); // UP
            $table->string('final_grade', 3)->nullable(); // NH
            $table->double('score')->nullable(); // NA
            $table->string('grade', 3)->nullable(); // NHU
            $table->double('index_score')->nullable(); // Indeks Feeder
            $table->string('description')->nullable();
            $table->boolean('is_publish')->default(false);
            $table->boolean('is_score_def')->default(false);
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.scores');
    }
};
