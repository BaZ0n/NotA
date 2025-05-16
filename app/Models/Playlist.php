<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class playlist extends Model
{
    protected $table = 'playlist'; // Явно указываем имя таблицы
    protected $fillable = ['playlistName', 'userID', 'photo_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}