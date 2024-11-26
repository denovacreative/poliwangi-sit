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
        Schema::create('siakad.thesis_requirement_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thesis_requirement_id');
            $table->string('requirement');
            $table->boolean('is_upload');
            $table->boolean('is_valid');
            $table->timestamp('validation_at');
            $table->timestamps();
            $table->foreign('thesis_requirement_id')->references('id')->on('thesis_requirements')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.thesis_requirement_validations');
    }
};
