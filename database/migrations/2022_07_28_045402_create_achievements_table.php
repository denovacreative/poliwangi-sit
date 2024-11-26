<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siakad.achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('name_en');
            $table->year('year');
            $table->enum('event_type', ['1', '2']);
            $table->enum('rating', ['1', '2','3','4','5']);
            $table->double('point');
            $table->string('position');
            $table->string('location');
            $table->string('organizer');
            $table->date('date_start');
            $table->date('date_end');
            $table->string('decree_number')->nullable();
            $table->date('decree_date')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->boolean('is_show_skpi')->default(false);
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->date('validation_date')->nullable();
            $table->unsignedBigInteger('achievement_group_id')->nullable();
            $table->unsignedBigInteger('achievement_type_id');
            $table->unsignedBigInteger('academic_period_id');
            $table->unsignedBigInteger('achievement_level_id');
            $table->uuid('student_id');
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('achievement_group_id')->references('id')->on('achievement_groups')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('achievement_level_id')->references('id')->on('achievement_levels')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('achievement_type_id')->references('id')->on('achievement_types')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.achievements');
    }
};
