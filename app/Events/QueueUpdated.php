<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $queue;
    public $action;
    public $index;
    public $track;

    public function __construct($action, $track = null, $index = null, $queue = [])
    {
        $this->action = $action; // 'add', 'remove', 'play'
        $this->track = $track;
        $this->index = $index;
        $this->queue = $queue;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('queue.sync');
    }

    public function broadcastWith()
    {
        return [
            'action' => $this->action,
            'track' => $this->track,
            'index' => $this->index,
            'queue' => $this->queue,
        ];
    }
}