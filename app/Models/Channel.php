<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $fillable = [];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
