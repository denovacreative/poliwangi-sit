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
        Schema::create('siakad.student_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->string('group')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('type', ['0', '1']);
            $table->string('description')->nullable();
            $table->string('decree_number')->nullable();
            $table->date('decree_date')->nullable();
            $table->boolean('is_mbkm')->default(false);
            $table->uuid('study_program_id');
            $table->unsignedBigInteger('academic_period_id');
            $table->unsignedBigInteger('student_activity_category_id');
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_activity_category_id')->references('id')->on('student_activity_categories')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.student_activities');
    }
};
