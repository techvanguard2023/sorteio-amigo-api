<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $groups = Group::where('organizer_id', $user->id)
            ->orWhereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['organizer', 'participants.user', 'participants.wishlistItems'])
            ->latest()
            ->get();
            
        return response()->json($groups);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'suggestedValue' => 'required|numeric|min:0',
            'drawDate' => 'required|date',
            'revealDate' => 'nullable|date|after_or_equal:drawDate',
            'rules' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        $group = $user->organizedGroups()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'suggested_value' => $validated['suggestedValue'],
            'draw_date' => $validated['drawDate'],
            'reveal_date' => $validated['revealDate'],
            'rules' => $validated['rules'],
        ]);

        // Adiciona o organizador como primeiro participante
        $group->participants()->create([
            'user_id' => $user->id,
            'status' => 'accepted'
        ]);

        return response()->json($group->load('participants'), 201);
    }

    public function show(Request $request, Group $group)
    {
        // Política de autorização: verificar se o usuário pertence ao grupo
        $this->authorize('view', $group);
        
        $user = $request->user();
        
        // Carrega os dados básicos do grupo incluindo wishlist dos participantes
        $group->load(['organizer', 'participants.user', 'participants.wishlistItems']);
        
        // Se o sorteio foi realizado, adiciona informações do sorteio
        if ($group->status === 'drawn') {
            // Encontra o participante do usuário autenticado
            $userParticipant = $group->participants->firstWhere('user_id', $user->id);
            
            if ($userParticipant) {
                // Carrega apenas o sorteio deste usuário específico
                $userParticipant->load(['pairing.receiver.user', 'pairing.receiver.wishlistItems']);
                
                // Adiciona as informações do sorteio ao grupo
                $groupArray = $group->toArray();
                $groupArray['my_draw'] = $userParticipant->pairing ? [
                    'receiver' => [
                        'name' => $userParticipant->pairing->receiver->user->name,
                        'notes' => $userParticipant->pairing->receiver->notes,
                        'address' => $userParticipant->pairing->receiver->address,
                        'wishlist' => $userParticipant->pairing->receiver->wishlistItems,
                    ]
                ] : null;
                
                return response()->json($groupArray);
            }
        }

        return response()->json($group);
    }
}