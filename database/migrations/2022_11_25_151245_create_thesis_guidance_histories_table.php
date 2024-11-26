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
        Schema::create('siakad.thesis_guidance_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('thesis_id');
            $table->uuid('employee_id');
            $table->integer('number_of_meeting');
            $table->string('topic');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('link')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_acc');
            $table->boolean('is_valid');
            $table->timestamps();
            $table->foreign('thesis_id')->references('id')->on('theses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.thesis_guidance_histories');
    }
};
