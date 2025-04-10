<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_plants_id')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('city');
            $table->json('media');
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
        $files = Storage::disk('public')->files('listings');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        Schema::dropIfExists('listings');
    }
};