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
        Schema::create('siakad.scholarships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('period_start_id');
            $table->integer('period_end_id');
            $table->date('date_start');
            $table->date('date_end');
            $table->double('total_budget');
            $table->unsignedBigInteger('scholarship_type_id');
            $table->timestamps();
            $table->foreign('scholarship_type_id')->references('id')->on('scholarship_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('period_start_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('period_end_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.scholarships');
    }
};
