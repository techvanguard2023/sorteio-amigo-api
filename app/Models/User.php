<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function participations()
    {
        return $this->hasMany(Participant::class);
    }

    public function organizedGroups()
    {
        return $this->hasMany(Group::class, 'organizer_id');
    }
}