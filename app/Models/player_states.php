<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class player_states extends Model
{
    protected $casts = [
        'queue' => 'array',
        'is_playing' => 'boolean',
    ];
}
