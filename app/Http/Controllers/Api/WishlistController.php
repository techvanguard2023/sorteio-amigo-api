<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Adiciona um novo item à lista de desejos de um participante.
     */
    public function store(Request $request, Participant $participant)
    {
        // Autorização: Garante que apenas o próprio usuário pode modificar sua lista.
        // Você deve criar uma ParticipantPolicy com a lógica: return $user->id === $participant->user_id;
        $this->authorize('update', $participant);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:2048',
        ]);

        $wishlistItem = $participant->wishlistItems()->create($validated);

        return response()->json($wishlistItem, 201);
    }

    /**
     * Atualiza um item da lista de desejos.
     */
    public function update(Request $request, Participant $participant, WishlistItem $wishlistItem)
    {
        // Autorização: Garante que o usuário logado é o dono deste item da lista.
        $this->authorize('update', $wishlistItem);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:2048',
        ]);

        $wishlistItem->update($validated);

        return response()->json($wishlistItem);
    }

    /**
     * Remove um item da lista de desejos.
     */
    public function destroy(Participant $participant, WishlistItem $wishlistItem)
    {
        // Autorização
        $this->authorize('delete', $wishlistItem);
        
        $wishlistItem->delete();

        return response()->json(null, 204); // Resposta "No Content"
    }


    /**
     * Atualiza o perfil do participante (notas e endereço).
     */
    public function updateProfile(Request $request, Participant $participant)
    {
        // Autorização: A mesma regra do 'store' se aplica aqui.
        $this->authorize('update', $participant);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
        ]);
        
        $participant->update($validated);

        return response()->json($participant);
    }
}