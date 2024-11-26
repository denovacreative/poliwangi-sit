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
        Schema::create('siakad.courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('alias')->nullable();
            $table->double('credit_total')->default(0);
            $table->double('credit_meeting')->default(0);
            $table->double('credit_practicum')->default(0);
            $table->double('credit_practice')->default(0);
            $table->double('credit_simulation')->default(0);
            $table->boolean('is_mku')->default(false)->nullable();
            $table->boolean('is_sap')->default(false)->nullable();
            $table->boolean('is_silabus')->default(false)->nullable();
            $table->boolean('is_bahan_ajar')->default(false)->nullable();
            $table->boolean('is_diktat')->default(false)->nullable();
            $table->uuid('study_program_id')->nullable();
            $table->string('course_type_id')->nullable();
            $table->unsignedBigInteger('course_group_id')->nullable();
            $table->unsignedBigInteger('scientific_field_id')->nullable();
            $table->uuid('rps_employee_id')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('course_type_id')->references('id')->on('course_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('course_group_id')->references('id')->on('course_groups')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('rps_employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.courses');
    }
};
