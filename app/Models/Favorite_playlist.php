<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class favorite_playlist extends Model
{
    protected $table = 'favorite_playlist';
    protected $fillable = ['playlistID', 'userID'];
}
