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
        Schema::create('simpeg.employee_active_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code',3)->unique()->nullable();
            $table->string('name');
            $table->boolean('is_exit')->nullable();
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
        Schema::dropIfExists('simpeg.employee_active_statuses');
    }
};
