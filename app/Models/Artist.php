<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class artist extends Model
{
    protected $table = 'artist'; // Явно указываем имя таблицы
    protected $fillable = ['artistName', 'photo_path', 'music_path', 'is_confirmed'];
}
