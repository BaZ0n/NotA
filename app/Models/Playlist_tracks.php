<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class playlist_tracks extends Model
{
    protected $table = 'playlist_tracks'; // Явно указываем имя таблицы
    protected $fillable = ['playlistID', 'trackID'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
