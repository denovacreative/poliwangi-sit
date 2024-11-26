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
        Schema::create('siakad.presences', function (Blueprint $table) {
            $table->id();
            $table->uuid('college_class_id');
            $table->uuid('class_schedule_id');
            $table->uuid('student_id');
            $table->integer('number_of_meeting');
            $table->date('date');
            $table->enum('status', ['0', 'H', 'I', 'S', 'A']);
            $table->timestamps();
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('class_schedule_id')->references('id')->on('class_schedules')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.presences');
    }
};
