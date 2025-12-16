<?php

namespace App\Policies;

use App\Models\WishlistItem;
use App\Models\User;

class WishlistItemPolicy
{
    public function delete(User $user, WishlistItem $wishlistItem)
    {
        // Carrega o participante e o grupo se não estiverem carregados
        if (!$wishlistItem->relationLoaded('participant')) {
            $wishlistItem->load('participant');
        }
        
        if ($wishlistItem->participant && !$wishlistItem->participant->relationLoaded('group')) {
            $wishlistItem->participant->load('group');
        }

        $organizerId = ($wishlistItem->participant && $wishlistItem->participant->group) ? $wishlistItem->participant->group->organizer_id : null;
        $participantUserId = $wishlistItem->participant ? $wishlistItem->participant->user_id : null;

        \Illuminate\Support\Facades\Log::info('Wishlist Delete Auth Check', [
            'user_id' => $user->id,
            'item_id' => $wishlistItem->id,
            'participant_id' => $wishlistItem->participant_id,
            'participant_user_id' => $participantUserId,
            'organizer_id' => $organizerId,
            'is_owner_strict' => ($participantUserId === $user->id),
            'is_owner_loose' => ($participantUserId == $user->id),
            'is_organizer_strict' => ($organizerId === $user->id),
            'is_organizer_loose' => ($organizerId == $user->id),
        ]);

        // 1. O dono do item pode deletar
        if ($wishlistItem->participant && $user->id == $participantUserId) {
            return true;
        }

        // 2. O organizador do grupo pode deletar
        if ($wishlistItem->participant && $wishlistItem->participant->group && $user->id == $organizerId) {
            return true;
        }

        return false;
    }


    public function update(User $user, WishlistItem $wishlistItem)
    {
        if (!$wishlistItem->relationLoaded('participant')) {
            $wishlistItem->load('participant');
        }

        return $wishlistItem->participant && $user->id === $wishlistItem->participant->user_id;
    }
}
