<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Retorna as informações do grupo usando o código de convite.
     */
    public function show($inviteCode)
    {
        $group = Group::where('invite_code', $inviteCode)
            ->with(['organizer', 'participants.user', 'participants.wishlistItems'])
            ->firstOrFail();
        
        return response()->json($group);
    }


    /**
     * Aceita um convite para um grupo usando o código de convite.
     */
    public function accept(Request $request, $invite_code)
    {
        // Encontra o grupo pelo código de convite ou falha com um erro 404
        $group = Group::where('invite_code', $invite_code)->firstOrFail();
        
        $user = Auth::user();

        // Verifica se o usuário já é um participante do grupo
        $isAlreadyParticipant = $group->participants()->where('user_id', $user->id)->exists();

        if ($isAlreadyParticipant) {
            // Retorna um erro de conflito se o usuário já estiver no grupo
            return response()->json(['message' => 'Você já faz parte deste grupo.'], 409);
        }

        // Adiciona o usuário como um novo participante com status 'accepted'
        $participant = $group->participants()->create([
            'user_id' => $user->id,
            'status' => 'accepted'
        ]);

        return response()->json([
            'message' => 'Bem-vindo(a) ao grupo!',
            'participant' => $participant->load('user') // Retorna o participante criado
        ], 201);
    }
}