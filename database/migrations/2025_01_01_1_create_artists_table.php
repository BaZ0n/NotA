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
        Schema::create('artist', function (Blueprint $table) {
            $table->id();
            $table->string('artistName');
            $table->boolean('is_confirmed');
            $table->string('photo_path')->nullable();
            $table->string('music_path')->nullable();
            $table->timestamps();
        });

        Schema::create('favorite_artist', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users');
            $table->unsignedBigInteger('artistID');
            $table->foreign('artistID')->references('id')->on('artist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
        Schema::dropIfExists('favorite_artist');
    }
};
