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
        Schema::create('siakad.judicial_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->string('periode', 5);
            $table->string('name');
            $table->date('date');
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.judicial_periods');
    }
};
