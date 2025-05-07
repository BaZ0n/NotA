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

        Schema::create('album', function (Blueprint $table) {
            $table->id();
            $table->string('albumName');
            $table->date('date_publish');
            $table->boolean('is_confirmed');
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('artistID');
            $table->foreign('artistID')->references('id')->on('artist');
            $table->timestamps();
        });

        Schema::create('favorite_album', function(Blueprint $table) {
            $table->unsignedBigInteger('userID');
            $table->foreign('userID')->references('id')->on('users');
            $table->unsignedBigInteger('albumID');
            $table->foreign('albumID')->references('id')->on('album');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
        Schema::dropIfExists('genre');
        Schema::dropIfExists('favorite_album');
    }
};
