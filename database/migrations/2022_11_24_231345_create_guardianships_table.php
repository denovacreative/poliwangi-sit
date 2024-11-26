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
        Schema::create('siakad.guardianships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('student_id');
            $table->uuid('employee_id');
            $table->date('date');
            $table->double('grade_semester')->default(0);
            $table->double('grade')->default(0);
            $table->double('credit_semester');
            $table->double('credit_total');
            $table->enum('guidance', ['0'])->nullable();
            $table->text('guidance_description')->nullable();
            $table->boolean('is_acc')->default(false);
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('siakad.guardianships');
    }
};
