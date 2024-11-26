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
        Schema::create('ref.rooms', function (Blueprint $table) {
            $table->id();
            $table->string('unitable_type')->nullable();
            $table->uuid('unitable_id')->nullable();
            $table->char('code', 10)->unique();
            $table->string('name');
            $table->string('location');
            $table->integer('capacity');
            $table->enum('type', ['class', 'lab', 'other']);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('ref.rooms');
    }
};
