<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\User;

class ParticipantPolicy
{
    /**
     * Determine if the user can update the participant.
     * Only the user who owns this participant record can update it.
     */
    public function update(User $user, Participant $participant): bool
    {
        return $user->id === $participant->user_id;
    }

    /**
     * Determine if the user can delete the participant.
     * Only the user who owns this participant record can delete it.
     */
    public function delete(User $user, Participant $participant): bool
    {
        return $user->id === $participant->user_id;
    }
}
