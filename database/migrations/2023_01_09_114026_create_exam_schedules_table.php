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
        Schema::create('siakad.exam_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('college_class_id');
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->uuid('employee_id_1')->nullable();
            $table->uuid('employee_id_2')->nullable();
            $table->enum('type', ['online', 'offline']);
            $table->string('location')->nullable();
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->timestamps();
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id_1')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id_2')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.exam_schedules');
    }
};
