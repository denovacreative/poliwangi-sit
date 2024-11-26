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
        Schema::create('siakad.course_curriculums', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('curriculum_id');
            $table->integer('semester');
            $table->double('credit_total')->default(0);
            $table->double('credit_meeting')->default(0);
            $table->double('credit_practicum')->default(0);
            $table->double('credit_practice')->default(0);
            $table->double('credit_simulation')->default(0);
            $table->boolean('is_mandatory')->default(false);
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.course_curriculums');
    }
};
