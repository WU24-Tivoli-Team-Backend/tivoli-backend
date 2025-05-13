<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Group extends Model
{
    use HasFactory, HasApiTokens;

    /** A group has many members (users) */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /** The transactions related to the group */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
