<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStamp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'stamp_id',
        'amusement_id',
        'collected_at'
    ];
    
    protected $casts = [
        'collected_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function stamp()
    {
        return $this->belongsTo(Stamp::class);
    }
    
    public function amusement()
    {
        return $this->belongsTo(Amusement::class);
    }
}
