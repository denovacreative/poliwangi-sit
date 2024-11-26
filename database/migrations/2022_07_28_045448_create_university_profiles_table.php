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
        Schema::create('ref.university_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('name_en');
            $table->string('alias');
            $table->string('phone_number')->nullable();
            $table->string('faximile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('street')->nullable();
            $table->string('neighbourhood')->nullable();
            $table->string('hamlet')->nullable();
            $table->string('village_lev_1')->nullable();
            $table->string('village_lev_2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('ownership_status')->nullable();
            $table->string('status')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('branch_unit')->nullable();
            $table->double('land_area_owned')->nullable();
            $table->double('land_area_not_owned')->nullable();
            $table->boolean('is_mbs')->nullable();
            $table->enum('acreditation', ['A', 'B', 'C', 'none'])->default('none');
            $table->string('acreditation_number')->nullable();
            $table->date('acreditation_date')->nullable();
            $table->string('establishment_number')->nullable();
            $table->date('establishment_date')->nullable();
            $table->string('operating_license_number')->nullable();
            $table->date('operating_license_date')->nullable();
            $table->uuid('employee_id')->nullable();
            $table->uuid('vice_chancellor')->nullable();
            $table->uuid('vice_chancellor_2')->nullable();
            $table->uuid('vice_chancellor_3')->nullable();
            $table->string('region_id')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('vice_chancellor')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('vice_chancellor_2')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('vice_chancellor_3')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.university_profiles');
    }
};
