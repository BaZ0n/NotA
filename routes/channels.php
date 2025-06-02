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

// Broadcast::channel('player.sync.{channelId}', function ($user, $channelId) {
//     // Проверка: участник ли этот пользователь канала
//     return \App\Models\Channel::where('id', $channelId)
//         ->whereHas('users', fn($q) => $q->where('id', $user->id))
//         ->exists();
// });

Broadcast::channel('player.sync.{channelId}', function ($user, $channelId) {
    // Только участники канала могут слушать
    return \App\Models\Channel::find($channelId)?->users->contains($user->id);
});