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
        Schema::create('simpeg.employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nip')->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('nidn')->nullable();
            $table->string('nidk')->nullable();
            $table->string('nupn')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nbm')->nullable();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('house_phone_number')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('campus_email')->nullable();
            $table->string('front_title')->nullable();
            $table->string('back_title')->nullable();
            $table->string('street')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->string('hamlet')->nullable();
            $table->string('village_lev_1')->nullable();
            $table->string('village_lev_2')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('cpns_number')->nullable();
            $table->date('cpns_date')->nullable();
            $table->string('appointment_number')->nullable();
            $table->date('appointment_end_date')->nullable();
            $table->string('family_name')->nullable();
            $table->string('family_nip')->nullable();
            $table->enum('marital_status', ['L', 'M', 'D', 'J'])->nullable();
            $table->string('picture')->default('default_employee_pic.jpg');
            $table->boolean('is_rps')->default(false);
            $table->unsignedBigInteger('employee_active_status_id')->nullable();
            $table->unsignedBigInteger('employee_status_id')->nullable();
            $table->unsignedBigInteger('employee_type_id')->nullable();
            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('scientific_field_id')->nullable();
            $table->unsignedBigInteger('religion_id');
            $table->string('country_id')->nullable();
            $table->unsignedBigInteger('family_profession_id')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('reg_id')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('employee_status_id')->references('id')->on('employee_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_active_status_id')->references('id')->on('employee_active_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_type_id')->references('id')->on('employee_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('scientific_field_id')->references('id')->on('scientific_fields')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('religion_id')->references('id')->on('religions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('family_profession_id')->references('id')->on('professions')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simpeg.employees');
    }
};
