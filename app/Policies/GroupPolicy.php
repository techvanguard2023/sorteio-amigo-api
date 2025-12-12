<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    /**
     * Determine if the user can view the group.
     * User can view if they are the organizer or a participant.
     */
    public function view(User $user, Group $group): bool
    {
        // Check if user is the organizer
        if ($group->organizer_id === $user->id) {
            return true;
        }

        // Check if user is a participant
        return $group->participants()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine if the user can update the group.
     * Only the organizer can update.
     */
    public function update(User $user, Group $group): bool
    {
        return $group->organizer_id === $user->id;
    }

    /**
     * Determine if the user can delete the group.
     * Only the organizer can delete.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->organizer_id === $user->id;
    }

    /**
     * Determine if the user can perform the draw.
     * Only the organizer can perform the draw.
     */
    public function draw(User $user, Group $group): bool
    {
        return $group->organizer_id === $user->id;
    }
}
