<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'description',
        'suggested_value',
        'draw_date',
        'reveal_date',
        'rules',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        // Gera um código de convite único ao criar um novo grupo
        static::creating(function ($group) {
            $group->invite_code = Str::random(8);
        });
    }
    
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function pairings()
    {
        return $this->hasMany(Pairing::class);
    }
}