<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TrackSynced implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $trackId;
    public $action;
    public $time;

    public $channelId;

    public function __construct($trackId, $action, $time)
    {
        $this->trackId = $trackId;
        $this->action = $action;
        $this->time = $time;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('track.sync' . $this->channelId);
    }
}