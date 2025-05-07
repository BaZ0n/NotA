<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class track extends Model
{
    protected $table = 'track'; // Явно указываем имя таблицы
    protected $fillable = ['trackName', 'duration', 'is_confirmed', 'path', 'albumID'];
}
