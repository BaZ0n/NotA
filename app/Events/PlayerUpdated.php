<?php

namespace App\Events;

use App\Models\player_state;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PlayerUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public player_state $player;

    public function __construct(player_state $player)
    {
        $this->player = $player;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('player.room.1'); // позже можно сделать динамически
    }

    public function broadcastWith(): array
    {
        return [
            'track_id' => $this->player->track_id,
            'is_playing' => $this->player->is_playing,
            'position' => $this->player->position,
            'queue_tracks' => json_decode($this->player->queue_tracks, true),
            'updated_by' => $this->player->updated_by,
            'updated_at' => $this->player->updated_at->toISOString()
        ];
    }
}
