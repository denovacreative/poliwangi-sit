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
        Schema::create('siakad.academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_start');
            $table->date('date_end');
            $table->boolean('is_national_holiday')->default(false);
            $table->boolean('is_academic_holiday')->default(false);
            $table->unsignedBigInteger('academic_period_id');
            $table->unsignedBigInteger('academic_activity_id');
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('academic_activity_id')->references('id')->on('academic_activities')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.academic_calendars');
    }
};
