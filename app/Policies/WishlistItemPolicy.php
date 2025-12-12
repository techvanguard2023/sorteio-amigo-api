<?php

namespace App\Policies;

use App\Models\WishlistItem;
use App\Models\User;

class WishlistItemPolicy
{
    public function delete(User $user, WishlistItem $wishlistItem)
    {
        // Carrega o participante se não estiver carregado
        if (!$wishlistItem->relationLoaded('participant')) {
            $wishlistItem->load('participant');
        }

        \Illuminate\Support\Facades\Log::info('Wishlist Delete Auth Check', [
            'user_id' => $user->id,
            'item_id' => $wishlistItem->id,
            'participant_id' => $wishlistItem->participant_id,
            'participant_user_id' => $wishlistItem->participant ? $wishlistItem->participant->user_id : 'null',
            'result' => ($wishlistItem->participant && $user->id === $wishlistItem->participant->user_id)
        ]);

        // Verifica se o participante existe e se o usuário é o dono
        return $wishlistItem->participant && $user->id === $wishlistItem->participant->user_id;
    }


    public function update(User $user, WishlistItem $wishlistItem)
    {
        if (!$wishlistItem->relationLoaded('participant')) {
            $wishlistItem->load('participant');
        }

        return $wishlistItem->participant && $user->id === $wishlistItem->participant->user_id;
    }
}
