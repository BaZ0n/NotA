<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $player;

    public function __construct($player)
    {
        $this->player = $player;
    }

    public function broadcastOn()
    {
        // Можно подключить по комнате/плейлисту, если нужно
        return new Channel('player');
    }

    public function broadcastWith()
    {
        return [
            'is_playing' => $this->player->is_playing,
            'position' => $this->player->position,
            'queue_tracks' => json_decode($this->player->queue_tracks, true),
            'updated_by' => $this->player->updated_by,
        ];
    }
}
