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
        Schema::create('siakad.thesis_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_upload')->default(false);
            $table->tinyText('description')->nullable();
            $table->enum('thesis_type', ['1', '2', '3']);
            $table->unsignedBigInteger('thesis_stage_id');
            $table->timestamps();
            $table->foreign('thesis_stage_id')->references('id')->on('thesis_stages')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.thesis_requirements');
    }
};
