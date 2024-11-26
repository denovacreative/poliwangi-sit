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
        Schema::create('ref.study_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id')->nullable();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('alias')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('faximile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->date('establishment_date')->nullable();
            $table->string('decree_number')->nullable();
            $table->date('decree_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['A', 'B', 'H', 'K', 'N'])->nullable();
            $table->enum('acreditation', ['A', 'B', 'C', 'none'])->nullable();
            $table->string('acreditation_number')->nullable();
            $table->date('acreditation_date')->nullable();
            $table->string('title')->nullable();
            $table->string('title_alias')->nullable();
            $table->string('title_en')->nullable();
            $table->uuid('major_id')->nullable();
            $table->unsignedBigInteger('education_level_id');
            $table->unsignedBigInteger('academic_period_id')->nullable();
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.study_programs');
    }
};
