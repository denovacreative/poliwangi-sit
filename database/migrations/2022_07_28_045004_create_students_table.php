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
        Schema::create('siakad.students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('study_program_id');
            $table->string('nim')->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->double('weight_body')->nullable();
            $table->double('height_body')->nullable();
            $table->enum('blood', ['A', 'B', 'AB', 'O'])->nullable();
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('nisn')->unique()->nullable();
            $table->string('kk')->nullable();
            $table->string('passport')->unique()->nullable();
            $table->string('phone_number')->nullable();
            $table->string('house_phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('campus_email')->nullable();
            $table->string('company')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_name')->nullable();
            $table->string('street')->nullable();
            $table->string('address')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->string('hamlet')->nullable();
            $table->string('village_lev_1')->nullable();
            $table->string('village_lev_2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('kps_number')->nullable();
            $table->boolean('is_kps')->default(false);
            $table->string('birth_certificate')->nullable();
            $table->enum('marital_status', ['L', 'M', 'D', 'J'])->nullable();
            $table->enum('jacket_size', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'])->nullable();
            $table->string('father_nik')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_birthplace')->nullable();
            $table->date('father_birthdate')->nullable();
            $table->integer('father_life_status')->default(1);
            $table->integer('father_relationship_status')->default(1);
            $table->unsignedBigInteger('father_education_id')->nullable();
            $table->unsignedBigInteger('father_profession_id')->nullable();
            $table->unsignedBigInteger('father_income_id')->nullable();
            $table->string('father_address')->nullable();
            $table->string('father_phone_number')->nullable();
            $table->string('father_email')->nullable();
            $table->string('mother_nik')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_birthplace')->nullable();
            $table->date('mother_birthdate')->nullable();
            $table->integer('mother_life_status')->default(1);
            $table->integer('mother_relationship_status')->default(1);
            $table->unsignedBigInteger('mother_education_id')->nullable();
            $table->unsignedBigInteger('mother_profession_id')->nullable();
            $table->unsignedBigInteger('mother_income_id')->nullable();
            $table->string('mother_address')->nullable();
            $table->string('mother_phone_number')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('guardian_nik')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_birthplace')->nullable();
            $table->date('guardian_birthdate')->nullable();
            $table->integer('guardian_life_status')->default(1);
            $table->integer('guardian_relationship_status')->default(1);
            $table->unsignedBigInteger('guardian_education_id')->nullable();
            $table->unsignedBigInteger('guardian_profession_id')->nullable();
            $table->unsignedBigInteger('guardian_income_id')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone_number')->nullable();
            $table->string('guardian_email')->nullable();
            $table->unsignedBigInteger('school_region_id')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_address')->nullable();
            $table->string('school_phone_number')->nullable();
            $table->string('school_diploma_number')->nullable();
            $table->string('diploma_file')->nullable();
            $table->double('tuition_fee')->default(0)->nullable();
            $table->string('picture')->default('student_default_pic.jpg');
            $table->boolean('is_valid')->default(false);
            $table->date('entry_date')->nullable();
            $table->uuid('consentration_id')->nullable();
            $table->unsignedBigInteger('academic_period_id')->nullable();
            $table->uuid('employee_id')->nullable();
            $table->unsignedBigInteger('lecture_system_id')->nullable();
            $table->string('student_status_id')->nullable();
            $table->unsignedBigInteger('registration_type_id')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->unsignedBigInteger('ethnic_id')->nullable();
            $table->string('country_id')->nullable();
            $table->unsignedBigInteger('transportation_id')->nullable();
            $table->unsignedBigInteger('profession_id')->nullable();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('class_group_id')->nullable();
            $table->unsignedBigInteger('registration_path_id')->nullable();
            $table->unsignedBigInteger('origin_school_id')->nullable();
            $table->unsignedBigInteger('type_of_stay_id')->nullable();
            $table->uuid('curriculum_id')->nullable();
            $table->string('reg_id')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('consentration_id')->references('id')->on('consentrations')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('lecture_system_id')->references('id')->on('lecture_systems')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_status_id')->references('id')->on('student_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('registration_type_id')->references('id')->on('registration_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('religion_id')->references('id')->on('religions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('ethnic_id')->references('id')->on('ethnics')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('transportation_id')->references('id')->on('transportations')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('class_group_id')->references('id')->on('class_groups')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('registration_path_id')->references('id')->on('registration_paths')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('origin_school_id')->references('id')->on('origin_schools')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('school_region_id')->references('id')->on('regions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('type_of_stay_id')->references('id')->on('type_of_stays')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('father_education_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('father_profession_id')->references('id')->on('professions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('father_income_id')->references('id')->on('incomes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('mother_education_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('mother_profession_id')->references('id')->on('professions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('mother_income_id')->references('id')->on('incomes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('guardian_education_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('guardian_profession_id')->references('id')->on('professions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('guardian_income_id')->references('id')->on('incomes')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.students');
    }
};
