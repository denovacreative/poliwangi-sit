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
        Schema::create('siakad.score_percentages', function (Blueprint $table) {
            $table->id();
            $table->uuid('college_class_id');
            $table->double('quiz')->default(0);
            $table->double('coursework')->default(0);
            $table->double('attendance')->default(0);
            $table->double('mid_exam')->default(0);
            $table->double('final_exam')->default(0);
            $table->double('practice')->default(0);
            $table->timestamps();
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.score_percentages');
    }
};
