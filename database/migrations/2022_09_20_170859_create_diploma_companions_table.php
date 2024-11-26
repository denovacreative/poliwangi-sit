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
        Schema::create('siakad.diploma_companions', function (Blueprint $table) {
            $table->id();
            $table->uuid('study_program_id');
            $table->unsignedBigInteger('education_level_id');
            $table->string('terms_acceptance');
            $table->string('terms_acceptance_en');
            $table->string('study');
            $table->string('type_education');
            $table->string('type_education_en');
            $table->string('next_type_education');
            $table->string('next_type_education_en');
            $table->string('kkni_level')->nullable();
            $table->string('profession_status')->nullable();
            $table->string('instruction_language');
            $table->string('instruction_language_en');
            $table->text('introduction');
            $table->text('introduction_en');
            $table->text('kkni_info');
            $table->text('kkni_info_en');
            $table->longText('work_ability');
            $table->longText('work_ability_en')->nullable();
            $table->longText('mastery_of_knowledge');
            $table->longText('mastery_of_knowledge_en')->nullable();
            $table->longText('special_attitude');
            $table->longText('special_attitude_en')->nullable();
            $table->timestamps();
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.diploma_companions');
    }
};
