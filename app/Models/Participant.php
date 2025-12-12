<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'notes',
        'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function pairing()
    {
        return $this->hasOne(Pairing::class, 'giver_participant_id');
    }
}