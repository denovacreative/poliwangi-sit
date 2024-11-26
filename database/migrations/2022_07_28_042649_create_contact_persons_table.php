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
        Schema::create('ref.contact_persons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('front_title')->nullable();
            $table->string('back_title')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->timestamps();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('religion_id')->references('id')->on('religions')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref.contact_persons');
    }
};
