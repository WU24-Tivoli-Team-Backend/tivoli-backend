<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'amusement_id',
        'group_id',
        'user_id',
        'stake_amount',
        'payout_amount',
        'stamp_id'
    ];

    protected $casts = [
        'stake_amount' => 'float',
        'payout_amount' => 'float',
    ];


    // Relationships

    /** The amusement the transaction belongs to */
    public function amusement()
    {
        return $this->belongsTo(Amusement::class);
    }

    /** The group the transaction belongs to */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /** The user the transaction belongs to */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stamp()
    {
        return $this->hasOne(Stamp::class);
    }
}
