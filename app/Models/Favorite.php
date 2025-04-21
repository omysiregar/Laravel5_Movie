<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'imdb_id',
    ];
    protected $table = 'favorite_movies';

    /**
     * Relasi ke user (jika diperlukan).
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
