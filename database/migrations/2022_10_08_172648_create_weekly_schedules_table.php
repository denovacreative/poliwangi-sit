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
        Schema::create('siakad.weekly_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('college_class_id');
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->time('time_start');
            $table->time('time_end');
            $table->enum('learning_method', ['online', 'offline', 'hybrid']);
            $table->timestamps();
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.weekly_schedules');
    }
};
