<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    /** @use HasFactory<\Database\Factories\StampFactory> */
    use HasFactory;

    protected $fillable = [
        'animal',
        'premium_attribute',
    ];

    //Relationships

    /** A stamp belongs to a user */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_stamp')
            ->withPivot('id', 'created_at', 'updated_at')
            ->withTimestamps();
    }
    
    public function userStamps()
    {
        return $this->hasMany(UserStamp::class);
    }

    /** And belongs to an amusement */
    public function amusement()
    {
        return $this->belongsTo(Amusement::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
