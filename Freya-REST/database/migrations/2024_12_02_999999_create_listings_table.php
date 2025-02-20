<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_plants_id');
            $table->string('city');
            $table->string('title');
            $table->text('description');
            $table->string('media');
            $table->boolean('sell');
            $table->integer('price');
            $table->timestamps();
            $table->foreign('user_plants_id')->references('id')->on('user_plants')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing');
    }
};