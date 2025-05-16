<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class playlist_moders extends Model
{
    protected $table = 'playlist_moders'; // Явно указываем имя таблицы
    protected $fillable = ['playlistID', 'userID'];
}
