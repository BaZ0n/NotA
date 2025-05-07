<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class album extends Model
{
    protected $table = 'album'; // Явно указываем имя таблицы
    protected $fillable = ['albumName', 'date_publish', 'is_confirmed', 'photo_path', 'artistID'];
}
