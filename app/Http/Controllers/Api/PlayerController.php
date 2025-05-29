<?php

namespace App\Http\Controllers;

use App\Models\player_state;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function play(Request $request)
    {
        $player = player_state::first(); // можно по room_id
        $player->is_playing = true;
        $player->updated_by = Auth::id();
        $player->updated_at = now();
        $player->save();

        broadcast(new \App\Events\PlayerUpdated($player))->toOthers();

        return response()->json(['status' => 'playing']);
    }

    public function pause(Request $request)
    {
        $player = player_state::first();
        $player->is_playing = false;
        $player->updated_by = Auth::id();
        $player->updated_at = now();
        $player->save();

        broadcast(new \App\Events\PlayerUpdated($player))->toOthers();

        return response()->json(['status' => 'paused']);
    }

    public function seek(Request $request)
    {
        $request->validate(['position' => 'required|integer']);

        $player = player_state::first();
        $player->position = $request->position;
        $player->updated_by = Auth::id();
        $player->updated_at = now();
        $player->save();

        broadcast(new \App\Events\PlayerUpdated($player))->toOthers();

        return response()->json(['status' => 'seeked']);
    }

    public function next(Request $request)
    {
        // Тут логика переключения на следующий трек
        // Например: берем из queue_tracks и удаляем первый
    }

    public function previous(Request $request)
    {
        // Аналогично, если хранишь историю — возвращаешься назад
    }

    public function addToQueue(Request $request)
    {
        $request->validate(['track_id' => 'required|integer']);

        $player = player_state::first();
        $queue = collect(json_decode($player->queue_tracks, true) ?? []);
        $queue->push($request->track_id);

        $player->queue_tracks = $queue->toJson();
        $player->updated_by = Auth::id();
        $player->save();

        broadcast(new \App\Events\PlayerUpdated($player))->toOthers();

        return response()->json(['status' => 'track added']);
    }
}

