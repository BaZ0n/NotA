<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_last_use extends Model
{
    protected $table = 'user_last_use'; // Явно указываем имя таблицы
    protected $fillable = ['userID', 'playlistID', 'trackID', 'volumeLevel'];
}
