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
        Schema::create('player_states', function (Blueprint $table) {
            $table->id();
            $table->string('room_id'); // Идентификатор комнаты/сессии
            $table->string('current_track');
            $table->integer('position')->default(0); // Позиция в миллисекундах
            $table->boolean('is_playing')->default(false);
            $table->json('queue')->nullable()->after('is_playing'); // Очередь треков в формате JSON
            $table->integer('current_index')->default(0)->after('queue'); // Индекс текущего трека в очереди
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_states');
    }
};
