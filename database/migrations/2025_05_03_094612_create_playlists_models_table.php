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
        Schema::create('playlist', function (Blueprint $table) {
            $table->id();
            $table->string('playlistName');
            $table->string('playlistPhoto');
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('playlist_moders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playlistID');
            $table->foreign('playlistID')->references('id')->on('playlist');
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('playlist_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playlistID');
            $table->foreign('playlistID')->references('id')->on('playlist');
            $table->unsignedBigInteger('trackID');
            $table->foreign('trackID')->references('id')->on('track');
            $table->timestamps();
        });

        Schema::create('favorite_playlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playlistID');
            $table->foreign('playlistID')->references('id')->on('playlist');
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist');
        Schema::dropIfExists('playlist_tracks');
        Schema::dropIfExists('favorite_playlist');
        Schema::dropIfExists('playlist_moders');
    }
};
