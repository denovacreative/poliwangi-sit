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
        Schema::create('siakad.user_accesses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->uuid('study_program_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['user_id', 'study_program_id']);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.user_accesses');
    }
};
