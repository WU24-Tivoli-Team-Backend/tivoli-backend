<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amusement_id',
    ];

    // Relationships

    /** The user who cast the vote */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** The amusement the vote is for */
    public function amusement()
    {
        return $this->belongsTo(Amusement::class);
    }
}
