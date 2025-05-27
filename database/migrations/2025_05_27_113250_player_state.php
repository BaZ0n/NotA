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
        Schema::create('player_state', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trackID');
            $table->foreign('trackID')->references('id')->on('track');
            $table->boolean('is_playing')->default(false);
            $table->unsignedBigInteger('updatedBy');
            $table->foreign('updatedBy')->references('id')->on('users');
            $table->json('queue_tracks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_state');
    }
};
