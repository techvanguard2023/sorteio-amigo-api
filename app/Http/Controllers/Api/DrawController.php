<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Pairing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrawController extends Controller
{
    public function store(Request $request, Group $group)
    {
        // Política de autorização: apenas o organizador pode sortear
        $this->authorize('draw', $group);

        $participants = $group->participants()->where('status', 'accepted')->get();

        if ($participants->count() < 2) {
            return response()->json(['message' => 'São necessários pelo menos 2 participantes confirmados.'], 422);
        }

        // Lógica do sorteio
        $givers = $participants->shuffle();
        $receivers = $givers->shuffle();

        // Garante que ninguém tire a si mesmo
        while ($this->hasSelfPairing($givers, $receivers)) {
            $receivers = $receivers->shuffle();
        }
        
        DB::transaction(function () use ($group, $givers, $receivers) {
            // Limpa sorteios anteriores, se houver
            $group->pairings()->delete();

            foreach ($givers as $index => $giver) {
                Pairing::create([
                    'group_id' => $group->id,
                    'giver_participant_id' => $giver->id,
                    'receiver_participant_id' => $receivers[$index]->id,
                ]);
            }

            $group->update(['status' => 'drawn']);
        });

        return response()->json(['message' => 'Sorteio realizado com sucesso!']);
    }

    /**
     * Retorna quem o usuário autenticado tirou no sorteio
     */
    public function show(Request $request, Group $group)
    {
        // Verifica se o usuário é participante do grupo
        $this->authorize('view', $group);

        // Verifica se o sorteio já foi realizado
        if ($group->status !== 'drawn') {
            return response()->json(['message' => 'O sorteio ainda não foi realizado.'], 400);
        }

        $user = $request->user();
        
        // Encontra o participante do usuário neste grupo
        $participant = $group->participants()->where('user_id', $user->id)->first();
        
        if (!$participant) {
            return response()->json(['message' => 'Você não é participante deste grupo.'], 403);
        }

        // Encontra quem este participante tirou
        $pairing = Pairing::where('group_id', $group->id)
            ->where('giver_participant_id', $participant->id)
            ->with(['receiver.user', 'receiver.wishlistItems'])
            ->first();

        if (!$pairing) {
            return response()->json(['message' => 'Sorteio não encontrado.'], 404);
        }

        return response()->json([
            'receiver' => [
                'name' => $pairing->receiver->user->name,
                'notes' => $pairing->receiver->notes,
                'address' => $pairing->receiver->address,
                'wishlist' => $pairing->receiver->wishlistItems,
            ]
        ]);
    }

    private function hasSelfPairing($givers, $receivers)
    {
        foreach ($givers as $index => $giver) {
            if ($giver->id === $receivers[$index]->id) {
                return true;
            }
        }
        return false;
    }
}