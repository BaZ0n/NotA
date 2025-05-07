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
        Schema::create('track', function(Blueprint $table) {
            $table-> id();
            $table->string('trackName');
            $table->float('duration');
            $table->boolean('is_confirmed');
            $table->string('path');
            $table->unsignedBigInteger('albumID');
            $table->foreign('albumID')->references('id')->on('album');
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
        Schema::dropIfExists('track');
        Schema::dropIfExists('track_authors');
    }
};
