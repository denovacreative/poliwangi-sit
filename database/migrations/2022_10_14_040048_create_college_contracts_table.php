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
        Schema::create('siakad.college_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('college_class_id');
            $table->longText('content')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->foreign('college_class_id')->references('id')->on('college_classes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.college_contracts');
    }
};
