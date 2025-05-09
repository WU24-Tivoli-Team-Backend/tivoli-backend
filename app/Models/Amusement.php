<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;

class Amusement extends Model
{
    /** @use HasFactory<\Database\Factories\AmusementFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'name',
        'type',
        'description',
        'image_url',
        'url',
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships

    /** The group that built the amusement */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /** All transactions on this amusement */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /** All stamps given from this amusement */
    public function stamps()
    {
        return $this->hasMany(Stamp::class);
    }

    /** All votes cast on this amusement */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
