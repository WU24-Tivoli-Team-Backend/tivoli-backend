<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    /** @use HasFactory<\Database\Factories\StampFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amusement_id',
        'animal',
        'premium_attribute',
    ];

    //Relationships

    /** A stamp belongs to a user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** And belongs to an amusement */
    public function amusement()
    {
        return $this->belongsTo(Amusement::class);
    }
}
