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
        Schema::create('siakad.thesis_exam_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('thesis_id');
            $table->unsignedBigInteger('thesis_stage_id');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_remedial')->default(false);
            $table->timestamps();
            $table->foreign('thesis_id')->references('id')->on('theses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('thesis_stage_id')->references('id')->on('thesis_stages')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.thesis_exam_schedules');
    }
};
