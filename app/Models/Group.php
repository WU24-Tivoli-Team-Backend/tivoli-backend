<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory;

    //Add this if we want to use a group name
    // protected $fillable = [
    //     'name',
    // ];

    //Relationships
    /** A group has many members(users) */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
