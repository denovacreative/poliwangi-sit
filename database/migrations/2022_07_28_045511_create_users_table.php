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
        Schema::create('auth.users', function (Blueprint $table) {
            $table->id();
            $table->string('unitable_type')->nullable();
            $table->unsignedBigInteger('unitable_id')->nullable();
            $table->string('userable_type')->nullable();
            $table->uuid('userable_id')->nullable();
            $table->string('sso_id')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('expired_at')->nullable();
            $table->string('picture')->default('default.png');
            $table->rememberToken();
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
        Schema::dropIfExists('auth.users');
    }
};
