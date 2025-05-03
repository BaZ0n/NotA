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
            $table->string('social_media_links');
            $table->timestamps();
        });

        Schema::create('genre', function(Blueprint $table) {
            $table->id();
            $table->string('genre');
            $table->timestamps();
        });

        Schema::create('album', function (Blueprint $table) {
            $table->id();
            $table->string('albumName');
            $table->date('date_publish');
            $table->boolean('is_confirmed');
            // $table->foreignId('genre')->constrained(
            //     table:'genre',indexName:'id'
            // );
            // $table->foreignId('artistID')->constrained(
            //     table:'artist', indexName:'id'
            // );
            $table->unsignedBigInteger('genreID');
            $table->foreign('genreID')->references('id')->on('genre');
            $table->unsignedBigInteger('artistID');
            $table->foreign('artistID')->references('id')->on('artist');
            $table->timestamps();
        });

        Schema::create('track', function(Blueprint $table) {
            $table-> id();
            $table->string('trackName');
            $table->integer('duration');
            $table->boolean('is_confirmed');
            $table->string('track_link');
            $table->foreignId('albumID')->constrained(
                table:'album', indexName:'id'
            );
            $table->timestamps();
        });

        Schema::create('track_authors', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artistID');
            $table->foreign('artistID')->references('id')->on('artist');
            $table->unsignedBigInteger('trackID');
            $table->foreign('trackID')->references('id')->on('track');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist');
        Schema::dropIfExists('album');
        Schema::dropIfExists('track');
        Schema::dropIfExists('genre');
        Schema::dropIfExists('album_authors');
    }
};
