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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->text('source');
            $table->text('content');
            $table->timestamps();
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null'); //on delete write deleted user?
            // $table->foreign('author_id')->references('id')->on('users')->onDelete('set null')->index();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};