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
        Schema::create('auth.user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('action' ,['r','i','u','d']);
            $table->timestamp('action_time');
            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();
            $table->string('table_name')->nullable();
            $table->text('route');
            $table->longText('data_before')->nullable();
            $table->longText('data_after')->nullable();
            $table->unsignedBigInteger('user_auth_log_id');
            $table->timestamps();
            $table->foreign('user_auth_log_id')->references('id')->on('user_auth_logs')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth.user_activity_logs');
    }
};
