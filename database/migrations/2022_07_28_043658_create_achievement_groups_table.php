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
        Schema::create('siakad.achievement_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('point');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('achievement_field_id');
            $table->unsignedBigInteger('achievement_type_id');
            $table->timestamps();
            $table->foreign('achievement_field_id')->references('id')->on('achievement_fields')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('achievement_type_id')->references('id')->on('achievement_types')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.achievement_groups');
    }
};
