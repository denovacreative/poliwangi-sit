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
        Schema::create('siakad.heregistrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('academic_period_id');
            $table->uuid('student_id');
            $table->string('attachment');
            $table->timestamp('payment_date');
            $table->double('tuition_fee')->default(0);
            $table->double('scholarship_amount')->default(0)->nullable();
            $table->double('subtotal')->default(0);
            $table->boolean('is_scholarship')->default(false)->nullable();
            $table->boolean('is_acc')->default(false);
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->timestamps();
            $table->foreign('academic_period_id')->references('id')->on('academic_periods')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siakad.heregistrations');
    }
};
