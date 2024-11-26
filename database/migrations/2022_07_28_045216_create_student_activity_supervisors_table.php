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
        Schema::create('siakad.student_activity_supervisors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->enum('role_type', ['0', '1']);
            $table->integer('number');
            $table->uuid('student_activity_id');
            $table->string('activity_category_id');
            $table->string('feeder_id')->nullable();
            $table->string('feeder_status')->nullable();
            $table->string('feeder_description')->nullable();
            $table->timestamps();
            $table->foreign('student_activity_id')->references('id')->on('student_activities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('activity_category_id')->references('id')->on('activity_categories')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.student_activity_supervisors');
    }
};
