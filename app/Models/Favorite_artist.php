<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class favorite_artist extends Model
{
    protected $fillable = ['userID', 'artistID'];
}
