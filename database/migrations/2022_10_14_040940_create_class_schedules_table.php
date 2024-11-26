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
        Schema::create('siakad.class_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('meeting_type_id');
            $table->uuid('college_class_id');
            $table->integer('meeting_number');
            $table->time('time_start');
            $table->time('time_end');
            $table->date('date');
            $table->enum('learning_method', ['online', 'offline', 'hybrid']);
            $table->double('credit');
            $table->string('link_meeting')->nullable();
            $table->string('location')->nullable();
            $table->string('attachment')->nullable();
            $table->string('presence_document')->nullable();
            $table->string('journal_document')->nullable();
            $table->enum('status', ['schedule', 'start', 'done', 'reschedule']);
            $table->text('material_plan')->nullable();
            $table->text('material_realization')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.class_schedules');
    }
};
