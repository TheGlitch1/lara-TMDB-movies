<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['oid', 'title', 'overview', 'poster_path', 'vote_average', 'release_date'];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function scopeMostVoted($query)
    {
        return $query->where('vote_average', '>=', 7);
    }

    public function scopeLeastVoted($query)
    {
        return $query->where('vote_average', '<', 6);
    }
}
