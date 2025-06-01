<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('track.sync', function ($user) {
    return $user !== null;
});

Broadcast::channel('queue.sync', function ($user) {
    return $user !== null;
});
