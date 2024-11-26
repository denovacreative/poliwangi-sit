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
        Schema::create('siakad.theses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('student_id');
            $table->unsignedBigInteger('thesis_stage_id')->nullable();
            $table->date('filing_date');
            $table->date('start_date');
            $table->date('finish_date');
            $table->text('topic');
            $table->text('topic_en')->nullable();
            $table->text('title');
            $table->text('title_en')->nullable();
            $table->longText('abstract');
            $table->string('decree_number');
            $table->date('decree_date');
            $table->enum('thesis_type', ['1', '2', '3']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_acc')->default(false);
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('thesis_stage_id')->references('id')->on('thesis_stages')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.theses');
    }
};
