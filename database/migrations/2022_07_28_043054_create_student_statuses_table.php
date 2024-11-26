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
        Schema::create('siakad.student_statuses', function (Blueprint $table) {
            $table->string('id',2)->primary();
            $table->string('name');
            $table->boolean('is_submited')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_college')->default(false);
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('siakad.student_statuses');
    }
};
