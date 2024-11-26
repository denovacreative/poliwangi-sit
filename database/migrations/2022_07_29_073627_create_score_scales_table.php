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
        Schema::create('siakad.score_scales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('study_program_id');
            $table->string('grade', 3);
            $table->double('index_score');
            $table->double('min_score');
            $table->double('max_score');
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->year('year_start')->nullable();
            $table->year('year_end')->nullable();
            $table->boolean('is_score_def')->default(false);
            $table->string('feeder_id')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.score_scales');
    }
};
