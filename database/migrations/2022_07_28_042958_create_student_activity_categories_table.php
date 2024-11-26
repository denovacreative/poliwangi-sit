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
        Schema::create('siakad.student_activity_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique()->nullable();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_mbkm')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.student_activity_categories');
    }
};
