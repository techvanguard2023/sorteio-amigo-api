<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pairing extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'giver_participant_id', 'receiver_participant_id'];

    public function giver()
    {
        return $this->belongsTo(Participant::class, 'giver_participant_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Participant::class, 'receiver_participant_id');
    }
}