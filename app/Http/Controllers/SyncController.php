<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\user;
use App\Models\Channel;
use App\Events\TrackSynced;
use App\Events\QueueUpdated;

class SyncController extends Controller
{
    public function createChannel(Request $request)
    {
        $user = Auth::user();
        $invitedUserId = $request->input('invitedUserId');
        if (!$invitedUserId || !User::find($invitedUserId)) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Проверяем, существует ли уже канал с этими двумя пользователями
        $existingChannel = Channel::whereHas('users', fn($q) => $q->where('user_id', $user->id))
            ->whereHas('users', fn($q) => $q->where('user_id', $invitedUserId))
            ->first();

        if ($existingChannel) {
            return response()->json(['channelId' => $existingChannel->id]);
        }

        // Создаём новый канал
        $channel = Channel::create();
        $channel->users()->attach([$user->id, $invitedUserId]);

        return response()->json(['channelId' => $channel->id]);
    }

    public function syncTrack(Request $request)
    {

        broadcast(new TrackSynced(
            $request->trackId,
            $request->action,
            $request->time,
            $request->channelId
        ))->toOthers();


        return response()->json(['status' => 'ok']);
    }
    public function syncQueue(Request $request) 
    {
        broadcast(new QueueUpdated(
            $request->action,
            $request->track,
            $request->index,
            $request->queue,
            $request->channelId
        ))->toOthers();

        return response()->json(['status' => 'ok']);
    }

}
