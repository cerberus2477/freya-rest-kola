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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id');
            $table->text('description');
            $table->longtext('content');
            $table->text('source');
            $table->timestamps();
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $files = Storage::disk('public')->files('articles');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        Schema::dropIfExists('articles');
    }
};