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
        Schema::create('siakad.judicial_participant_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('judicial_participant_id');
            $table->uuid('judicial_requirement_id');
            $table->string('attachment')->nullable();
            $table->date('validation_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_valid');
            $table->timestamps();
            $table->foreign('judicial_participant_id')->references('id')->on('judicial_participants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('judicial_requirement_id')->references('id')->on('judicial_requirements')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.judicial_participant_requirements');
    }
};
